@extends('layouts.main')

@section('title', 'Детали заказа #' . $basket->id)

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Детали заказа #{{ $basket->id }}</h1>

            <form action="{{ route('admin.orders.destroy', $basket->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить заказ #{{ $basket->id }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить заказ</button>
            </form>
        </div>

        <!-- Информация о заказе -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Информация о заказе</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID заказа:</strong> {{ $basket->id }}</p>
                        <p><strong>Статус:</strong>
                            @if($basket->status === 'completed')
                                <span class="badge bg-success">Завершен</span>
                            @else
                                <span class="badge bg-warning">{{ $basket->status }}</span>
                            @endif
                        </p>
                        <p><strong>Дата создания:</strong> {{ $basket->created_at->format('d.m.Y H:i:s') }}</p>
                        <p><strong>Дата обновления:</strong> {{ $basket->updated_at->format('d.m.Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Пользователь:</strong> {{ $basket->user->name ?? 'Неизвестно' }}</p>
                        <p><strong>Email:</strong> {{ $basket->user->email ?? 'Нет email' }}</p>
                        <p><strong>ID пользователя:</strong> {{ $basket->user_id }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Таблица товаров -->
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Товары в заказе</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                        <tr>
                            <th>ID товара</th>
                            <th>Название</th>
                            <th>Цена за единицу</th>
                            <th>Количество</th>
                            <th>Сумма</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $totalSum = 0;
                        @endphp

                        @forelse($basket->items as $item)
                            @php
                                $price = $item->product->discount_price ?? $item->product->price;
                                $subtotal = $price * $item->quantity;
                                $totalSum += $subtotal;
                            @endphp
                            <tr>
                                <td>{{ $item->product_id }}</td>
                                <td>
                                    {{ $item->product->name ?? 'Товар удален' }}
                                    @if(!$item->product)
                                        <span class="badge bg-danger">Товар не найден</span>
                                    @endif
                                </td>
                                <td>{{ number_format($price, 2) }} ₽</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($subtotal, 2) }} ₽</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Нет товаров в заказе</td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot class="table-secondary">
                        <tr>
                            <th colspan="4" class="text-end">Итого:</th>
                            <th>{{ number_format($totalSum, 2) }} ₽</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Назад к списку заказов</a>
        </div>
    </div>
@endsection
