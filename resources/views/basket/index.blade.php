@extends('layouts.main')

@section('title', 'Корзина')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Корзина</h1>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
                <p class="font-medium">Успешно!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                <p class="font-medium">Ошибка!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if($basket && $basket->items->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b-2 border-gray-200">
                        <tr>
                            <th class="text-left py-4 px-6 font-semibold text-gray-600">Товар</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-600">Цена</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-600">Количество</th>
                            <th class="text-left py-4 px-6 font-semibold text-gray-600">Сумма</th>
                            <th class="text-center py-4 px-6 font-semibold text-gray-600">Действия</th>
                        </tr>
                        </thead>
                        <tbody id="basket-table-body">
                        @foreach($basket->items as $item)
                            @php
                                $price = $item->product->discount_price ?? $item->product->price;
                                $subtotal = $price * $item->quantity;
                            @endphp
                            <tr id="item-row-{{ $item->id }}" class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded-lg">
                                        <div>
                                            <a href="{{ route('products.show', $item->product) }}" class="font-semibold text-gray-800 hover:text-cyan-600 transition">
                                                {{ $item->product->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-semibold text-gray-800">{{ number_format($price, 2) }} ₽</span>
                                    @if($item->product->discount_price)
                                        <p class="text-xs text-gray-400 line-through">{{ number_format($item->product->price, 2) }} ₽</p>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ route('basket.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center">
                                        <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-cyan-500 hover:text-white rounded-lg transition text-sm">Обновить</button>
                                    </form>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-lg text-gray-900">{{ number_format($subtotal, 2) }} ₽</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form action="{{ route('basket.remove', $item->id) }}" method="POST" onsubmit="return confirm('Вы уверены?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                        <tr>
                            <th colspan="3" class="text-right py-4 px-6 font-semibold text-gray-600">Итого:</th>
                            <th colspan="2" id="basket-total-sum-cell" class="py-4 px-6 text-2xl font-bold text-gray-900">{{ number_format($totalSum, 2) }} ₽</th>
                        </tr>
                        <tr>
                            <td colspan="5" class="py-4 px-6 text-right">
                                <form action="{{ route('basket.clear') }}" method="POST" class="d-inline" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition mr-3" onclick="return confirm('Вы уверены?')">
                                        Очистить корзину
                                    </button>
                                </form>
                                <form action="{{ route('basket.checkout') }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold rounded-xl transition shadow-lg">
                                        Оформить заказ
                                    </button>
                                </form>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-2">Ваша корзина пуста</h2>
                <p class="text-gray-500 mb-6">Добавьте товары в корзину, чтобы продолжить покупки.</p>
                <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold rounded-xl transition shadow-lg">
                    Перейти в каталог
                </a>
            </div>
        @endif
    </div>
@endsection
