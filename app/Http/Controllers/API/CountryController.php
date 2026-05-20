<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Country::filter($request);

        return CountryResource::collection($query->paginate($request->get('per_page', 10)));
    }
}
