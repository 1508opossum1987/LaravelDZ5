@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Товары</h1>
            @if(auth()->user()->canManageProducts())
                <a href="{{ route('products.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    + Создать товар
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-600 text-white p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @php
            $activeProducts = $products->whereNull('deleted_at')->where('active', true);
            $trashedProducts = $products->whereNotNull('deleted_at');
        @endphp

        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-4">Активные товары</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($activeProducts as $product)
                    <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                             class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-white font-bold text-lg mb-2">{{ $product->name }}</h2>
                            <p class="text-gray-400 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                            <div class="flex justify-between items-center mb-3">
                                <span
                                    class="text-blue-400 font-bold text-xl">{{ number_format($product->price, 2) }} ₽</span>
                                @if($product->discount_price)
                                    <span class="text-gray-500 line-through text-sm">{{ number_format($product->discount_price, 2) }} ₽</span>
                                @endif
                            </div>
                            <div class="flex justify-between items-center">
                                <a href="{{ route('products.show', $product) }}"
                                   class="text-blue-400 hover:text-blue-300 text-sm">
                                    Подробнее →
                                </a>

                                @if(auth()->user()->canManageProducts())
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.edit', $product) }}"
                                           class="text-yellow-400 hover:text-yellow-300 text-sm">
                                            Редактировать
                                        </a>

                                        <form action="{{ route('products.destroy', $product) }}"
                                              method="POST"
                                              onsubmit="return confirm('Удалить товар «{{ $product->name }}»?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-400 text-lg">Нет активных товаров</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if(auth()->user()->isAdmin() && $trashedProducts->count() > 0)
            <div>
                <h2 class="text-2xl font-bold text-white mb-4">Корзина (удаленные товары)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($trashedProducts as $product)
                        <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg opacity-75">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover grayscale">
                            <div class="p-4">
                                <h2 class="text-gray-400 font-bold text-lg mb-2">{{ $product->name }}</h2>
                                <p class="text-gray-500 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-gray-400 font-bold text-xl">{{ number_format($product->price, 2) }} ₽</span>
                                    @if($product->discount_price)
                                        <span class="text-gray-600 line-through text-sm">{{ number_format($product->discount_price, 2) }} ₽</span>
                                    @endif
                                </div>
                                @if($product->deleted_at)
                                    <p class="text-gray-500 text-xs mb-3">
                                        Удален: {{ $product->deleted_at->format('d.m.Y H:i') }}</p>
                                @endif
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('products.restore', $product->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Восстановить товар «{{ $product->name }}»?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                                            Восстановить
                                        </button>
                                    </form>

                                    <form action="{{ route('products.forceDestroy', $product->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('ВНИМАНИЕ! Товар «{{ $product->name }}» будет удален навсегда. Это действие нельзя отменить. Продолжить?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                                            Удалить навсегда
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
@endsection
