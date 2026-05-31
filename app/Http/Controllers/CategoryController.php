<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Models\Category;
use App\Rules\CountCategoryRule;
use App\Rules\RussianCharsRule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    private const int ITEMS_PER_PAGE = 7;

    public function index(): View
    {
        $categories = Category::query()
            ->withTrashed()
            ->with(['children' => function ($query) {
                $query->withTrashed()->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->paginate(self::ITEMS_PER_PAGE);

        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    public function create(): View
    {
        $parents = Category::query()->where('active', true)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('categories.create', ['parents' => $parents]);
    }

    public function store(CategoryStoreRequest $categoryStoreRequest): RedirectResponse
    {
        $validated = $categoryStoreRequest->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $validated['active'] = $categoryStoreRequest->has('active');

        $category = Category::query()->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', "Категория '{$category->name} успешно создана!");

    }

    public function show(Category $category): View
    {
        $category->load(['parent', 'children', 'products' => function ($query) {
            $query->where('active', true)->limit(20);
        }]);
        return view('categories.show', ['category' => $category]);
    }

    public function edit(Category $category): View
    {
        $parents = Category::where('id', '!=', $category->id)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        return view('categories.edit', [
            'category' => $category,
            'parents' => $parents
        ]);
    }

    public function update(CategoryStoreRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['name'] !== $category->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['active'] = $request->has('active') ? true : false;

        try {
            $category->name = $validated['name'];
            $category->active=$validated['active'];
            $category->parent_id=$validated['parent_id'];
        }
        catch (\Exception $exception)
        {
            abort(500, $exception->getMessage());
        }

        return redirect()
            ->route('categories.index')
            ->with('success', "Категория '{$category->name}' успешно обновлена!");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $categoryName = $category->name;

        if ($category->products()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', "Нельзя удалить категорию '{$categoryName}', так как у нее есть товары!");
        }

        $ids = $this->getAllChildIds($category->id);

        $ids[] = $category->id;

        Category::whereIn('id', $ids)->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', "Категория '{$categoryName}' и все её подкатегории успешно удалены!");
    }

    private function getAllChildIds(int $parentId): array
    {
        $ids = [];

        $children = Category::where('parent_id', $parentId)->get();

        foreach ($children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllChildIds($child->id));
        }

        return $ids;
    }

    public function restore($id): RedirectResponse
    {
        $category = Category::withTrashed()
            ->findOrFail($id);
        $categoryName = $category->name;

        if ($category->trashed()) {
            $category->restore();
            return redirect()
                ->route('categories.index')
                ->with('success', "Категория'{$categoryName}' успешно восстановлена!");
        }

        return redirect()
            ->route('categories.index')
            ->with('success', "Категория '{$categoryName}' не удалялась!");
    }

    public function forceDestroy($id): RedirectResponse
    {
        $category = Category::withTrashed()
            ->findOrFail($id);
        $categoryName = $category->name;

        if ($category->trashed()) {
            $category->forceDelete();
            return redirect()
                ->route('categories.index')
                ->with('success', "Категория '{$categoryName}' успешно удалена из корзины!");
        }

        return redirect()
            ->route('categories.index')
            ->with('success', "Категория '{$categoryName}' не находится в корзине!");
    }

    public function trashed(): View
    {
        $categories = Category::onlyTrashed()->orderBy('name')->get();
        return view('categories.trashed', ['categories' => $categories]);
    }

    public function categoryProducts(Category $category): View
    {
        return view('categories.products',
            ['category' => $category,
                'products' => $category->products()->paginate(12)]);
    }
}
