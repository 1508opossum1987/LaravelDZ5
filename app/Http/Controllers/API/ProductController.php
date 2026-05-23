<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::filter($request)
            ->sortable($request);

        return ProductResource::collection($query->paginate($request->get('per_page', 10)));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $product = Product::query()
            ->create($request->input());

        return response()->json(['data' => $product], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, $id = null)
    {
        if ($id) {
            $product = Product::findOrFail($id);
        } else {
            $productName = $request->query('name');

            if (!$productName) {
                return response()->json([
                    'message' => 'Не указано имя продукта'
                ], Response::HTTP_BAD_REQUEST);
            }

            $product = Product::where('name', $productName)->first();
        }

        $product->delete();

        return response()->json([
            'message' => "Продукт '{$product->name}' успешно удален!"
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id = null)
    {
        if ($id) {
            $product = Product::findOrFail($id);
        } else {
            $productName = $request->query('name');

            if (!$productName) {
                return response()->json([
                    'message' => 'Не указано название продукта'
                ], Response::HTTP_BAD_REQUEST);
            }

            $product = Product::where('name', $productName)->first();
        }

        $request->validate([
            'name' => 'sometimes|required|string' . $product->id,
            'active' => 'sometimes|required|boolean'
        ]);

        if (!$request->has('name') && !$request->has('active')) {
            return response()->json([
                'message' => 'Не передано ни одного поля для обновления'
            ], Response::HTTP_BAD_REQUEST);
        }

        $product->update($request->only(['name', 'active']));

        return response()->json([
            'message' => "Продукт '{$product->name}' успешно обновлен!"
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return response()->json(['data' => new ProductResource($product)], Response::HTTP_OK);
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->restore();

        return response()->json([
            'data' => new ProductResource($product),
            'message' => "Продукт '{$product->name}' успешно восстановлен"],
            Response::HTTP_OK);
    }

    public function forceDestroy($id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);

        $product->forceDelete();

        return response()->json([
            'data' => new ProductResource($product),
            'message' => "продукт '{$product->name}' успешно удален без возможности восстановления"],
            Response::HTTP_OK);
    }

    public function trashed(Request $request): AnonymousResourceCollection
    {
        $query = Product::onlyTrashed()
            ->sortable($request);

        return ProductResource::collection($query->paginate($request->get('per_page', 10)));
    }
}
