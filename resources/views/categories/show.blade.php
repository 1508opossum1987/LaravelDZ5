@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-gray-300">
                ← Все категории
            </a>
        </div>

        <div class="bg-gray-900 rounded-lg p-6 mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">{{ $category->name }}</h1>

            @if($category->parent)
                <p class="text-gray-400">
                    Родительская категория:
                    <a href="{{ route('categories.show', $category->parent) }}" class="text-blue-400 hover:text-blue-300">
                        {{ $category->parent->name }}
                    </a>
                </p>
            @endif

            @if($category->children->count() > 0)
                <div class="mt-4">
                    <p class="text-gray-400 mb-2">Подкатегории:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($category->children as $child)
                            <a href="{{ route('categories.show', $child) }}"
                               class="bg-gray-800 hover:bg-gray-700 text-white px-3 py-1 rounded-lg text-sm transition">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="flex gap-3 mt-6">
                    <a href="{{ route('categories.edit', $category) }}"
                       class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        Редактировать
                    </a>
                </div>
            @endif
        </div>

        @if($category->products->count() > 0)
            <h2 class="text-2xl font-bold text-white mb-4">Товары в этой категории</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($category->products as $product)
                    <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-white font-bold text-lg mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-400 text-sm mb-3">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-blue-400 font-bold text-xl">{{ number_format($product->price, 2) }} ₽</span>
                                @if($product->discount_price)
                                    <span class="text-gray-500 line-through text-sm">{{ number_format($product->discount_price, 2) }} ₽</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-900 rounded-lg p-12 text-center">
                <p class="text-gray-400 text-lg">В этой категории пока нет товаров</p>
            </div>
        @endif
    </div>
@endsection
