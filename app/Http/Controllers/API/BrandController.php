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
        $query = Brand::filter($request);

        return BrandResource::collection($query->paginate($request->get('per_page', 10)));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $brand =  Brand::query()
            ->create($request->input());

        return response()->json(['data' => $brand], Response::HTTP_CREATED);
    }

    public function destroyByName(Request $request)
    {
        $brandName = $request->query('name');

        if (!$brandName) {
            return response()->json([
                'message' => 'Не указано имя бренда'
            ], Response::HTTP_BAD_REQUEST);
        }

        $brand = Brand::where('name', $brandName)->first();

        if (!$brand) {
            return response()->json([
                'message' => "Бренд '{$brandName}' не найден!"
            ], Response::HTTP_NOT_FOUND);
        }

        if ($brand->products()->exists()) {
            return response()->json([
                'message' => "Нельзя удалить бренд '{$brandName}', так как у него есть товары!"
            ], Response::HTTP_CONFLICT);
        }

        $brand->delete();

        return response()->json([
            'message' => "Бренд '{$brandName}' успешно удален!"
        ], Response::HTTP_OK);
    }

    public function destroyById($id)
    {
        $brand = Brand::find($id);

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

        $brandName = $brand->name;
        $brand->delete();

        return response()->json([
            'message' => "Бренд '{$brandName}' успешно удален!"
        ], Response::HTTP_OK);
    }
}
