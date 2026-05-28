@extends('layouts.main')

@section('title', 'Управление заказами')

@section('content')
    <div class="container">
        <h1>Управление заказами</h1>

        <!-- Фильтры -->
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <label for="user_id" class="form-label">ID пользователя</label>
                    <input type="text" name="user_id" id="user_id" class="form-control" placeholder="ID пользователя" value="{{ request('user_id') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Дата от</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Дата до</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Фильтровать</button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Сбросить</a>
                </div>
            </div>
        </form>

        <!-- Таблица заказов -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                <tr>
                    <th>ID заказа</th>
                    <th>Пользователь</th>
                    <th>Email</th>
                    <th>Дата создания</th>
                    <th>Количество товаров</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @forelse($baskets as $basket)
                    <tr>
                        <td>{{ $basket->id }}</td>
                        <td>{{ $basket->user->name ?? 'Неизвестно' }}</td>
                        <td>{{ $basket->user->email ?? 'Неизвестно' }}</td>
                        <td>{{ $basket->created_at->format('d.m.Y H:i:s') }}</td>
                        <td>{{ $basket->totalItems() }}</td>
                        <td>{{ number_format($basket->totalSum(), 2) }} ₽</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $basket->id) }}" class="btn btn-sm btn-info">Смотреть</a>

                            <form action="{{ route('admin.orders.destroy', $basket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Вы уверены, что хотите удалить заказ #{{ $basket->id }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Заказов не найдено</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Пагинация -->
        <div class="d-flex justify-content-center">
            {{ $baskets->links() }}
        </div>
    </div>
@endsection
