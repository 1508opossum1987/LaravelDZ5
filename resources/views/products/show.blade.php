@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-300">
                ← Все товары
            </a>
        </div>

        <div class="bg-gray-900 rounded-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/2">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>

                <div class="md:w-1/2 p-8">
                    <h1 class="text-4xl font-bold text-white mb-4">{{ $product->name }}</h1>

                    <div class="mb-6">
                        @if($product->discount_price)
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-green-500">{{ number_format($product->discount_price, 2) }} ₽</span>
                                <span class="text-xl text-gray-500 line-through">{{ number_format($product->price, 2) }} ₽</span>
                                <span class="bg-red-600 text-white px-2 py-1 rounded text-sm">
                                    -{{ round((1 - $product->discount_price / $product->price) * 100) }}%
                                </span>
                            </div>
                        @else
                            <span class="text-3xl font-bold text-blue-400">{{ number_format($product->price, 2) }} ₽</span>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-white mb-3">Характеристики</h3>
                        <div class="space-y-2 text-gray-300">
                            <p><span class="text-gray-400">Категория:</span>
                                <a href="{{ route('categories.show', $product->category) }}" class="text-blue-400 hover:text-blue-300">
                                    {{ $product->category->name ?? 'Не указана' }}
                                </a>
                            </p>
                            <p><span class="text-gray-400">Бренд:</span>
                                <a href="{{ route('brands.show', $product->brand) }}" class="text-blue-400 hover:text-blue-300">
                                    {{ $product->brand->name ?? 'Не указан' }}
                                </a>
                            </p>
                            <p><span class="text-gray-400">Страна производства:</span>
                                <a href="{{ route('countries.show', $product->country) }}" class="text-blue-400 hover:text-blue-300">
                                    {{ $product->country->name ?? 'Не указана' }}
                                </a>
                            </p>
                            @if($product->price_from)
                                <p><span class="text-gray-400">Старая цена:</span> {{ number_format($product->price_from, 2) }} ₽</p>
                            @endif
                        </div>
                    </div>

                    @if($product->description)
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-white mb-3">Описание</h3>
                            <p class="text-gray-300 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif
                    @if(auth()->user()->canManageProducts())
                        <div class="flex gap-3 pt-4 border-t border-gray-700">
                            <a href="{{ route('products.edit', $product) }}"
                               class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                Редактировать товар
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
