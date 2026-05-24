<?php

namespace App\Http\Controllers;

use App\Events\ProductCreateEvent;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const int ITEMS_PER_PAGE = 12;

    public function index(): View
    {
        $products = Product::query()
            ->withTrashed()
            ->where('active', true)
            ->orderByDesc('created_at')
            ->paginate(self::ITEMS_PER_PAGE);

        return view('products.index', (
        ['products' => $products]
        ));
    }

    public function create(): View
    {
        $categories = Category::where('active', true)->orderBy('name')->get();
        $brands = Brand::where('active', true)->orderBy('name')->get();
        $countries = Country::where('active', true)->orderBy('name')->get();
        return view('products.create', [
            'categories' => $categories,
            'brands' => $brands,
            'countries' => $countries]);
    }

    public function store(ProductStoreRequest $productStoreRequest): RedirectResponse
    {
        $validated = $productStoreRequest->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $validated['active'] = $productStoreRequest->has('active');

        if ($productStoreRequest->hasFile('img_path')) {
            $validated['img_path'] = $productStoreRequest->file('img_path')->store('products', 'public');
        }
        try {
            $product = Product::query()->create($validated);
            $user = \Auth::user();

            event(new ProductCreateEvent(
                $user,
                $product,
                'Created Product ' . $product->name
            ));
        } catch (\Exception $exception) {
            if (
                $productStoreRequest->hasFile('img_path') &&
                File::exists('storage/' . $validated['img_path'])) {
                File::delete('storage/' . $validated['img_path']);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', "Продукт '{$product->name}' успешно создан!");
    }

    public function show(Product $product): View
    {
        $product->load(['category', 'brand', 'country']);

        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product): View
    {
        Cache::forget('categories');
        if (Cache::has('categories')) {
            $categories = Cache::get('categories');
        } else {
            $categories = Category::where('active', true)->orderBy('name')->get()->toArray();
            Cache::put('categories', $categories, 60 * 24);
        }
        $brands = Brand::where('active', true)->orderBy('name')->get();
        $countries = Country::where('active', true)->orderBy('name')->get();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'countries' => $countries]);
    }

    public function update(ProductStoreRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['active'] = $request->has('active') ? true : false;

        $filePath = null;
        if ($request->hasFile('img_path')) {
            $filePath = $request->file('img_path')->store('products', 'public');
        }

        try {
            $product->country_id = $validated['country_id'];
            $product->brand_id = $validated['brand_id'];
            $product->category_id = $validated['category_id'];
            $product->price = $validated['price'];
            $product->active = $validated['active'];
            $product->name = $validated['name'];
            $product->description = $validated['description'];
            $product->discount_price = $validated['discount_price'];
            $product->price_from = $validated['price_from'];
            if ($filePath) {
                if (File::exists('storage/' . $product->img_path)) {
                    File::delete('storage/' . $product->img_path);
                }
                $product->img_path = $filePath;
            }
            $product->save();
        } catch (\Exception $exception) {
            if ($filePath && File::exists($filePath)) {
                File::delete($filePath);
            }
        }


        return redirect()
            ->route('products.index')
            ->with('success', "Продукт '{$product->name}' успешно обновлен!");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $productName = $product->name;

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', "Продукт '{$productName}' успешно удален!");
    }

    public function restore($id): RedirectResponse
    {
        $product = Product::withTrashed()
            ->findOrFail($id);
        $productName = $product->name;

        if ($product->trashed()) {
            $product->restore();
            return redirect()
                ->route('products.index')
                ->with('success', "Продукт '{$productName}' успешно восстановлен!");
        }

        return redirect()
            ->route('products.index')
            ->with('success', "Продукт '{$productName}' не удалялся!");
    }

    public function forceDestroy($id): RedirectResponse
    {
        $product = Product::withTrashed()
            ->findOrFail($id);
        $productName = $product->name;

        if ($product->trashed()) {
            $product->forceDelete();
            return redirect()
                ->route('products.index')
                ->with('success', "Продукт '{$productName}' успешно удален из корзины!");
        }

        return redirect()
            ->route('products.index')
            ->with('success', "Продукт '{$productName}' не находится в корзине!");
    }

    public function trashed(): View
    {
        $products = Product::onlyTrashed()->orderBy('name')->get();
        return view('products.trashed', ['products' => $products]);
    }
}
