<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Category::filter($request)
            ->sortable($request);

        return CategoryResource::collection($query->paginate($request->get('per_page', 10)));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $category = Category::query()
            ->create($request->input());

        return response()->json(['data' => $category], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, $id = null)
    {
        if ($id) {
            $category = Category::findOrFail($id);
        } else {
            $categoryName = $request->query('name');

            if (!$categoryName) {
                return response()->json([
                    'message' => 'Не указано имя категории'
                ], Response::HTTP_BAD_REQUEST);
            }

            $category = Category::where('name', $categoryName)->first();
        }

        if ($category->products()->exists()) {
            return response()->json([
                'message' => "Нельзя удалить категорию '{$category->name}', так как у нее есть товары!"
            ], Response::HTTP_CONFLICT);
        }

        $category->delete();

        return response()->json([
            'message' => "Категория '{$category->name}' успешно удалена!"
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id = null)
    {
        if ($id) {
            $category = Category::findOrFail($id);
        } else {
            $categoryName = $request->query('name');

            if (!$categoryName) {
                return response()->json([
                    'message' => 'Не указано имя категории'
                ], Response::HTTP_BAD_REQUEST);
            }

            $category = Category::where('name', $categoryName)->first();
        }

        $request->validate([
            'name' => 'sometimes|required|string|unique:categories,name' . $category->id,
            'active' => 'sometimes|required|boolean'
        ]);

        if (!$request->has('name') && !$request->has('active')) {
            return response()->json([
                'message' => 'Не передано ни одного поля для обновления'
            ], Response::HTTP_BAD_REQUEST);
        }

        $category->update($request->only(['name', 'active']));

        return response()->json([
            'message' => "Категория '{$category->name}' успешно обновлена!"
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json(['data' => new CategoryResource($category)], Response::HTTP_OK);
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        $category->restore();

        return response()->json([
            'data' => new CategoryResource($category),
            'message' => "Категория '{$category->name}' успешно восстановлена"],
            Response::HTTP_OK);
    }

    public function forceDestroy($id)
    {
        $category = Category::onlyTrashed()
            ->findOrFail($id);

        $category->forceDelete();

        return response()->json([
            'data' => new CategoryResource($category),
            'message' => "Категория '{$category->name}' успешно удалена без возможности восстановления"],
            Response::HTTP_OK);
    }

    public function trashed(Request $request): AnonymousResourceCollection
    {
        $query = Category::onlyTrashed()
            ->sortable($request);

        return CategoryResource::collection($query->paginate($request->get('per_page', 10)));
    }
}
