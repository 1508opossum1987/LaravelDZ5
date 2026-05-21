<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BrandController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Brand::filter($request)
            ->sortable($request);

        return BrandResource::collection($query->paginate($request->get('per_page', 10)));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $brand = Brand::query()
            ->create($request->input());

        return response()->json(['data' => $brand], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, $id = null)
    {
        if ($id) {
            $brand = Brand::find($id);
        } else {
            $brandName = $request->query('name');

            if (!$brandName) {
                return response()->json([
                    'message' => 'Не указано имя бренда'
                ], Response::HTTP_BAD_REQUEST);
            }

            $brand = Brand::where('name', $brandName)->first();
        }

        if (!$brand) {
            return response()->json([
                'message' => 'Бренд не найден'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($brand->products()->exists()) {
            return response()->json([
                'message' => "Нельзя удалить бренд '{$brand->name}', так как у него есть товары!"
            ], Response::HTTP_CONFLICT);
        }

        $brand->delete();

        return response()->json([
            'message' => "Бренд '{$brand->name}' успешно удален!"
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id = null)
    {
        if ($id) {
            $brand = Brand::find($id);
        } else {
            $brandName = $request->query('name');

            if (!$brandName) {
                return response()->json([
                    'message' => 'Не указано имя бренда'
                ], Response::HTTP_BAD_REQUEST);
            }

            $brand = Brand::where('name', $brandName)->first();
        }

        if (!$brand) {
            return response()->json([
                'message' => 'Бренд не найден'
            ], Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'name' => 'sometimes|required|string|unique:brands,name' . $brand->id,
            'active' => 'sometimes|required|boolean'
        ]);

        if (!$request->has('name') && !$request->has('active')) {
            return response()->json([
                'message' => 'Не передано ни одного поля для обновления'
            ], Response::HTTP_BAD_REQUEST);
        }

        $brand->update($request->only(['name', 'active']));

        return response()->json([
            'message' => "Бренд '{$brand->name}' успешно обновлен!"
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);

        return response()->json(['data' => new BrandResource($brand)], Response::HTTP_OK);
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);

        $brand->restore();

        return response()->json([
            'data' => new BrandResource($brand),
            'message' => "Бренд '{$brand->name}' успешно восстановлен"],
            Response::HTTP_OK);
    }

    public function forceDestroy($id)
    {
        $brand = Brand::onlyTrashed()
            ->findOrFail($id);

        $brand->forceDelete();

        return response()->json([
            'data' => new BrandResource($brand),
            'message' => "Бренд '{$brand->name}' успешно удален без возможности восстановления"],
            Response::HTTP_OK);
    }

    public function trashed(Request $request): AnonymousResourceCollection
    {
        $query = Brand::onlyTrashed()
            ->sortable($request);

        return BrandResource::collection($query->paginate($request->get('per_page', 10)));
    }
}
