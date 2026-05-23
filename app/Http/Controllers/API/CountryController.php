<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CountryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Country::filter($request)
            ->sortable($request);

        return CountryResource::collection($query->paginate($request->get('per_page', 10)));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $country = Country::query()
            ->create($request->input());

        return response()->json(['data' => $country], Response::HTTP_CREATED);
    }

    public function destroy(Request $request, $id = null)
    {
        if ($id) {
            $country = Country::findOrFail($id);
        } else {
            $countryName = $request->query('name');

            if (!$countryName) {
                return response()->json([
                    'message' => 'Не указано имя страны'
                ], Response::HTTP_BAD_REQUEST);
            }

            $country = Country::where('name', $countryName)->first();
        }

        if ($country->products()->exists()) {
            return response()->json([
                'message' => "Нельзя удалить страну '{$country->name}', так как у нее есть товары!"
            ], Response::HTTP_CONFLICT);
        }

        $country->delete();

        return response()->json([
            'message' => "Страна '{$country->name}' успешно удалена!"
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $id = null)
    {
        if ($id) {
            $country = Country::findOrFail($id);
        } else {
            $countryName = $request->query('name');

            if (!$countryName) {
                return response()->json([
                    'message' => 'Не указано имя страны'
                ], Response::HTTP_BAD_REQUEST);
            }

            $country = Country::where('name', $countryName)->first();
        }

        $request->validate([
            'name' => 'sometimes|required|string|unique:countries,name' . $country->id,
            'active' => 'sometimes|required|boolean'
        ]);

        if (!$request->has('name') && !$request->has('active')) {
            return response()->json([
                'message' => 'Не передано ни одного поля для обновления'
            ], Response::HTTP_BAD_REQUEST);
        }

        $country->update($request->only(['name', 'active']));

        return response()->json([
            'message' => "Страна '{$country->name}' успешно обновлена!"
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $country = Country::findOrFail($id);

        return response()->json(['data' => new CountryResource($country)], Response::HTTP_OK);
    }

    public function restore($id)
    {
        $country = Country::onlyTrashed()->findOrFail($id);

        $country->restore();

        return response()->json([
            'data' => new CountryResource($country),
            'message' => "Страна '{$country->name}' успешно восстановлена"],
            Response::HTTP_OK);
    }

    public function forceDestroy($id)
    {
        $country = Country::onlyTrashed()
            ->findOrFail($id);

        $country->forceDelete();

        return response()->json([
            'data' => new CountryResource($country),
            'message' => "Страна '{$country->name}' успешно удалена без возможности восстановления"],
            Response::HTTP_OK);
    }

    public function trashed(Request $request): AnonymousResourceCollection
    {
        $query = Country::onlyTrashed()
            ->sortable($request);

        return CountryResource::collection($query->paginate($request->get('per_page', 10)));
    }
}
