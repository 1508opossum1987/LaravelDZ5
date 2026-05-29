@extends('layouts.main')

@section('title', 'Управление заказами')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Управление заказами</h1>

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

        <!-- Фильтры -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">ID пользователя</label>
                    <input type="text" name="user_id" id="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent" placeholder="ID пользователя" value="{{ request('user_id') }}">
                </div>
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Дата от</label>
                    <input type="date" name="date_from" id="date_from" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent" value="{{ request('date_from') }}">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Дата до</label>
                    <input type="date" name="date_to" id="date_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent" value="{{ request('date_to') }}">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold rounded-lg transition shadow-md">
                        Фильтровать
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                        Сбросить
                    </a>
                </div>
            </form>
        </div>

        <!-- Таблица заказов -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold text-gray-600">ID заказа</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-600">Пользователь</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-600">Email</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-600">Дата создания</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-600">Кол-во товаров</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-600">Сумма</th>
                        <th class="text-center py-4 px-6 font-semibold text-gray-600">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($baskets as $basket)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-4 px-6 font-medium text-gray-900">{{ $basket->id }}</td>
                            <td class="py-4 px-6 text-gray-700">{{ $basket->user->name ?? 'Неизвестно' }}</td>
                            <td class="py-4 px-6 text-gray-500">{{ $basket->user->email ?? 'Неизвестно' }}</td>
                            <td class="py-4 px-6 text-gray-500">{{ $basket->created_at->format('d.m.Y H:i:s') }}</td>
                            <td class="py-4 px-6 text-gray-700">{{ $basket->totalItems() }}</td>
                            <td class="py-4 px-6 font-bold text-gray-900">{{ number_format($basket->totalSum(), 2) }} ₽</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.orders.show', $basket->id) }}" class="px-3 py-1.5 bg-cyan-500 hover:bg-cyan-600 text-white text-sm font-medium rounded-lg transition">
                                        Смотреть
                                    </a>
                                    <form action="{{ route('admin.orders.destroy', $basket->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить заказ #{{ $basket->id }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-500">
                                <div class="text-4xl mb-2">📦</div>
                                <p>Заказов не найдено</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Пагинация -->
        <div class="mt-8">
            {{ $baskets->links() }}
        </div>
    </div>
@endsection
