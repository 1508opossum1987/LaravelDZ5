<?php

namespace App\Http\Controllers;

use App\Http\Requests\Brand\BrandStoreRequest;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    private const int ITEMS_PER_PAGE = 8;

    public function index(): View
    {
        $brands = Brand::query()
            ->withTrashed()
            ->where('active', true)
            ->orderBy('name')
            ->paginate(self::ITEMS_PER_PAGE);;

        return view('brands.index', [
            'brands' => $brands
        ]);
    }

    public function create(): View
    {
        return view('brands.create');
    }

    public function store(BrandStoreRequest $brandStoreRequest): RedirectResponse
    {
        $validated = $brandStoreRequest->validated();
        $validated['slug'] = Str::slug($validated['name']);
        $imgPath = $brandStoreRequest->file('img_path')->store('brands', 'public');
        $validated['active'] = $brandStoreRequest->has('active');

        $brand = Brand::query()->create($validated);
        $brand->image()->create([
            'url' => $imgPath,
        ]);

        return redirect()
            ->route('brands.index')
            ->with('success', "Бренд '{$brand->name}' успешно создан!");
    }

    public function show(Brand $brand): View
    {
        $brand->load(['products' => function ($query) {
            $query->where('active', true)->limit(20);
        }]);

        return view('brands.show', compact('brand'));
    }

    public function edit(Brand $brand): View
    {
        return view('brands.edit', ['brand' => $brand]);
    }

    public function update(BrandStoreRequest $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('img_path')) {
            if ($brand->image && File::exists('storage/' . $brand->image->url)) {
                File::delete('storage/' . $brand->image->url);
            }

            $imgPath = $request->file('img_path')->store('brands', 'public');
            $brand->image()->updateOrCreate([
                'imageable_id' => $brand->id,
                'imageable_type' => Brand::class,
            ],
                [
                    'url' => $imgPath
                ]);
        }

        if ($validated['name'] !== $brand->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['active'] = $request->has('active') ? true : false;

        $brand->update($validated);

        return redirect()
            ->back()
            ->with('success', "Бренд '{$brand->name}' успешно обновлен!");
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brandName = $brand->name;

        if ($brand->products()->exists()) {
            return redirect()
                ->route('brands.index')
                ->with('error', "Нельзя удалить бренд '{$brandName}', так как у него есть товары!");
        }

        $brand->delete();

        return redirect()
            ->route('brands.index')
            ->with('success', "Бренд '{$brandName}' успешно удален!");
    }

    public function restore($id): RedirectResponse
    {
        $brand = Brand::withTrashed()
            ->findOrFail($id);
        $brandName = $brand->name;

        if ($brand->trashed()) {
            $brand->restore();
            return redirect()
                ->route('brands.index')
                ->with('success', "Бренд '{$brandName}' успешно восстановлен!");
        }

        return redirect()
            ->route('brands.index')
            ->with('success', "Бренд '{$brandName}' не удалялся!");
    }

    public function forceDestroy($id): RedirectResponse
    {
        $brand = Brand::withTrashed()
            ->findOrFail($id);
        $brandName = $brand->name;

        if ($brand->trashed()) {
            $brand->forceDelete();
            return redirect()
                ->route('brands.index')
                ->with('success', "Бренд '{$brandName}' успешно удален из корзины!");
        }

        return redirect()
            ->route('brands.index')
            ->with('success', "Бренд '{$brandName}' не находится в корзине!");
    }

    public function trashed(): View
    {
        $brands = Brand::onlyTrashed()->orderBy('name')->get();
        return view('brands.trashed', ['brands' => $brands]);
    }
}
