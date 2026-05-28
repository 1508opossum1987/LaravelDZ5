@extends('layouts.main')

@section('title', 'Корзина')

@section('content')
    <div class="container">
        <h1>Корзина</h1>

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

        @if($basket && $basket->items->count() > 0)
            <!-- Таблица товаров -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                    <tr>
                        <th>Фото</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($basket->items as $item)
                        @php
                            $price = $item->product->discount_price ?? $item->product->price;
                            $subtotal = $price * $item->quantity;
                        @endphp
                        <tr id="item-row-{{ $item->id }}">
                            <td>
                                @if($item->product->img_path)
                                    <img src="{{ asset('storage/' . $item->product->img_path) }}" alt="{{ $item->product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="Нет фото" style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $item->product->id) }}">
                                    {{ $item->product->name }}
                                </a>
                            </td>
                            <td>{{ number_format($price, 2) }} ₽</td>
                            <td style="width: 150px;">
                                <form action="{{ route('basket.update', $item->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm me-2" style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-primary">Обновить</button>
                                </form>
                            </td>
                            <td>{{ number_format($subtotal, 2) }} ₽</td>
                            <td>
                                <form action="{{ route('basket.remove', $item->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить товар из корзины?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                    <tr>
                        <th colspan="4" class="text-end">Итого:</th>
                        <th colspan="2">{{ number_format($totalSum, 2) }} ₽</th>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end">
                            <form action="{{ route('basket.clear') }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите очистить всю корзину?')">
                                @csrf
                                <button type="submit" class="btn btn-warning">Очистить корзину</button>
                            </form>

                            <form action="{{ route('basket.checkout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">Оформить заказ</button>
                            </form>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <!-- Пустая корзина -->
            <div class="alert alert-info text-center">
                <h4>Ваша корзина пуста</h4>
                <p>Добавьте товары в корзину, чтобы продолжить покупки.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Перейти в каталог</a>
            </div>
        @endif
    </div>
@endsection
