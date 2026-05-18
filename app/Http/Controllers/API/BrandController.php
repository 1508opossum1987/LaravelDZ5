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

        $brand =  Brand::query()->create($request->input());

        return response()->json(['data' => $brand], Response::HTTP_CREATED);
    }
}
