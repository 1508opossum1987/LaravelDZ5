@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('countries.index') }}" class="text-gray-400 hover:text-gray-300">
                ← Все страны
            </a>
        </div>

        <div class="bg-gray-900 rounded-lg p-6 mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">{{ $country->name }}</h1>
                    <p class="text-gray-400">
                        Статус: <span class="{{ $country->active ? 'text-green-400' : 'text-red-400' }}">
                        {{ $country->active ? 'Активна' : 'Неактивна' }}
                    </span>
                    </p>
                </div>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('countries.edit', $country) }}"
                       class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        Редактировать
                    </a>
                @endif
            </div>
        </div>

        @if($country->products->count() > 0)
            <h2 class="text-2xl font-bold text-white mb-4">Товары из страны {{ $country->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($country->products as $product)
                    <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-white font-bold text-lg mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-400 text-sm mb-3">{{ Str::limit($product->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span
                                    class="text-blue-400 font-bold text-xl">{{ number_format($product->price, 2) }} ₽</span>
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
                <p class="text-gray-400 text-lg">Из этой страны пока нет товаров</p>
            </div>
        @endif
    </div>
@endsection
