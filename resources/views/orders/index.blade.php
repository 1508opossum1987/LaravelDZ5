@extends('layouts.main')

@section('title', 'Мои заказы')

@section('content')
    <div class="container">
        <h1>Мои заказы</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($completedBaskets && $completedBaskets->count() > 0)
            <div class="row">
                @foreach($completedBaskets as $basket)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Заказ #{{ $basket->id }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Дата заказа:</strong> {{ $basket->created_at->format('d.m.Y H:i:s') }}</p>
                                <p><strong>Количество товаров:</strong> {{ $basket->totalItems() }}</p>
                                <p><strong>Общая сумма:</strong> <span class="fw-bold">{{ number_format($basket->totalSum(), 2) }} ₽</span></p>
                                <p><strong>Статус:</strong>
                                    <span class="badge bg-success">Завершен</span>
                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="collapse" data-bs-target="#order-details-{{ $basket->id }}">
                                    Показать товары
                                </button>
                            </div>

                            <!-- Скрытый блок с деталями товаров -->
                            <div class="collapse" id="order-details-{{ $basket->id }}">
                                <div class="card-body bg-light">
                                    <h6>Товары в заказе:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-secondary">
                                            <tr>
                                                <th>Название</th>
                                                <th>Цена</th>
                                                <th>Количество</th>
                                                <th>Сумма</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($basket->items as $item)
                                                @php
                                                    $price = $item->product->discount_price ?? $item->product->price;
                                                    $subtotal = $price * $item->quantity;
                                                @endphp
                                                <tr>
                                                    <td>{{ $item->product->name ?? 'Товар удален' }}</td>
                                                    <td>{{ number_format($price, 2) }} ₽</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ number_format($subtotal, 2) }} ₽</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot class="table-secondary">
                                            <tr>
                                                <th colspan="3" class="text-end">Итого:</th>
                                                <th>{{ number_format($basket->totalSum(), 2) }} ₽</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Пустая история заказов -->
            <div class="alert alert-info text-center">
                <h4>У вас пока нет заказов</h4>
                <p>Сделайте первый заказ в нашем магазине.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Перейти в каталог</a>
            </div>
        @endif
    </div>
@endsection
