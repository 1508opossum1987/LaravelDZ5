<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Country::filter($request);

        return response()->json($query->paginate($request->get('per_page', 10)));
    }
}
