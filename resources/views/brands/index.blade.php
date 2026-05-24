@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Бренды</h1>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('brands.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    + Создать бренд
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

        <!-- Активные бренды -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-4">Активные бренды</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($brands->whereNull('deleted_at') as $brand)
                    <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-white mb-2">
                                {{ $brand->name }}
                            </h2>

                            <div class="text-gray-400 text-sm mb-4">
                                Товаров: {{ $brand->products()->count() }}
                            </div>

                            <div class="flex justify-between items-center">
                                <a href="{{ route('brands.show', $brand) }}"
                                   class="text-blue-400 hover:text-blue-300 text-sm">
                                    Подробнее →
                                </a>

                                @if (auth()->user()?->isAdmin())
                                    <div class="flex gap-2">
                                        <a href="{{ route('brands.edit', $brand) }}"
                                           class="text-yellow-400 hover:text-yellow-300 text-sm">
                                            Редактировать
                                        </a>

                                        <form action="{{ route('brands.destroy', $brand) }}"
                                              method="POST"
                                              onsubmit="return confirm('Удалить бренд «{{ $brand->name }}»? Товары этого бренда останутся без бренда.')">
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
                        <p class="text-gray-400 text-lg">Нет активных брендов</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Удаленные бренды (корзина) -->
        @php
            $trashedBrands = $brands->whereNotNull('deleted_at');
        @endphp

        @if(auth()->user()?->isAdmin()&&$trashedBrands->count() > 0)
            <div>
                <h2 class="text-2xl font-bold text-white mb-4">Корзина (удаленные бренды)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($trashedBrands as $brand)
                        <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg opacity-75">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-400 mb-2">
                                    {{ $brand->name }}
                                </h2>

                                <div class="text-gray-500 text-sm mb-4">
                                    Товаров: {{ $brand->products()->count() }}
                                    @if($brand->deleted_at)
                                        <br>Удален: {{ $brand->deleted_at->format('d.m.Y H:i') }}
                                    @endif
                                </div>

                                <div class="flex justify-end gap-2">
                                    <!-- Кнопка восстановления -->
                                    <form action="{{ route('brands.restore', $brand) }}"
                                          method="POST"
                                          onsubmit="return confirm('Восстановить бренд «{{ $brand->name }}»?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                                            Восстановить
                                        </button>
                                    </form>

                                    <!-- Кнопка окончательного удаления -->
                                    <form action="{{ route('brands.forceDestroy', $brand) }}"
                                          method="POST"
                                          onsubmit="return confirm('ВНИМАНИЕ! Бренд «{{ $brand->name }}» будет удален навсегда. Это действие нельзя отменить. Продолжить?')">
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
            {{ $brands->links() }}
        </div>
    </div>
@endsection
