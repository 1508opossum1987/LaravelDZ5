@extends('layouts.main')

@section('title', 'Мои заказы')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Мои заказы</h1>

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

        @if($completedBaskets && $completedBaskets->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($completedBaskets as $basket)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 px-6 py-4">
                            <h5 class="text-white font-bold text-lg">Заказ #{{ $basket->id }}</h5>
                        </div>
                        <div class="p-6">
                            <div class="space-y-2 mb-4">
                                <p class="flex justify-between">
                                    <span class="text-gray-500">Дата заказа:</span>
                                    <span class="font-medium text-gray-700">{{ $basket->created_at->format('d.m.Y H:i:s') }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-500">Количество товаров:</span>
                                    <span class="font-medium text-gray-700">{{ $basket->totalItems() }}</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-500">Общая сумма:</span>
                                    <span class="font-bold text-2xl text-cyan-600">{{ number_format($basket->totalSum(), 2) }} ₽</span>
                                </p>
                                <p class="flex justify-between">
                                    <span class="text-gray-500">Статус:</span>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Завершен</span>
                                </p>
                            </div>

                            <button type="button" onclick="toggleDetails({{ $basket->id }})" class="w-full px-4 py-2 bg-gray-100 hover:bg-cyan-500 hover:text-white text-gray-700 font-medium rounded-lg transition">
                                Показать товары
                            </button>

                            <!-- Скрытый блок с деталями товаров -->
                            <div id="order-details-{{ $basket->id }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                                <h6 class="font-semibold text-gray-800 mb-3">Товары в заказе:</h6>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-left py-2 px-3 font-semibold text-gray-600">Название</th>
                                            <th class="text-left py-2 px-3 font-semibold text-gray-600">Цена</th>
                                            <th class="text-left py-2 px-3 font-semibold text-gray-600">Кол-во</th>
                                            <th class="text-left py-2 px-3 font-semibold text-gray-600">Сумма</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($basket->items as $item)
                                            @php
                                                $price = $item->product->discount_price ?? $item->product->price;
                                                $subtotal = $price * $item->quantity;
                                            @endphp
                                            <tr class="border-b border-gray-100">
                                                <td class="py-2 px-3 text-gray-700">{{ $item->product->name ?? 'Товар удален' }}</td>
                                                <td class="py-2 px-3 text-gray-500">{{ number_format($price, 2) }} ₽</td>
                                                <td class="py-2 px-3 text-gray-700">{{ $item->quantity }}</td>
                                                <td class="py-2 px-3 font-medium text-gray-900">{{ number_format($subtotal, 2) }} ₽</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot class="bg-gray-50">
                                        <tr>
                                            <th colspan="3" class="text-right py-2 px-3 font-semibold text-gray-600">Итого:</th>
                                            <th class="py-2 px-3 font-bold text-gray-900">{{ number_format($basket->totalSum(), 2) }} ₽</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Пустая история заказов -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📦</div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-2">У вас пока нет заказов</h2>
                <p class="text-gray-500 mb-6">Сделайте первый заказ в нашем магазине.</p>
                <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold rounded-xl transition shadow-lg">
                    Перейти в каталог
                </a>
            </div>
        @endif
    </div>

    <script>
        function toggleDetails(orderId) {
            const details = document.getElementById(`order-details-${orderId}`);
            if (details.classList.contains('hidden')) {
                details.classList.remove('hidden');
            } else {
                details.classList.add('hidden');
            }
        }
    </script>
@endsection
