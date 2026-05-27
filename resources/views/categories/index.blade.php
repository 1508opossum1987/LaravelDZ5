@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        @if (auth()->user()->isAdmin())
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-white">Категории</h1>
                <a href="{{ route('categories.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    + Создать категорию
                </a>
            </div>
        @endif

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
            $activeCategories = $categories->whereNull('deleted_at')->where('active', true);
            $trashedCategories = $categories->whereNotNull('deleted_at');
        @endphp

        <div class="mb-12">
            <h2 class="text-2xl font-bold text-white mb-4">Активные категории</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($activeCategories as $category)
                    <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-white mb-2">
                                {{ $category->name }}
                            </h2>

                            @if($category->parent)
                                <div class="text-gray-400 text-sm mb-1">
                                    Родитель: {{ $category->parent->name }}
                                </div>
                            @endif

                            <div class="text-gray-400 text-sm mb-4">
                                Товаров: {{ $category->products()->count() }}
                                @if($category->children->count() > 0)
                                    <br>Подкатегорий: {{ $category->children->count() }}
                                @endif
                            </div>

                            <div class="flex justify-between items-center">
                                <a href="{{ route('categories.show', $category) }}"
                                   class="text-blue-400 hover:text-blue-300 text-sm">
                                    Подробнее →
                                </a>

                                @if(auth()->user()->isAdmin())
                                    <div class="flex gap-2">
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="text-yellow-400 hover:text-yellow-300 text-sm">
                                            Редактировать
                                        </a>

                                        <form action="{{ route('categories.destroy', $category) }}"
                                              method="POST"
                                              onsubmit="return confirm('Удалить категорию «{{ $category->name }}»? Все подкатегории также будут удалены.')">
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
                        <p class="text-gray-400 text-lg">Нет активных категорий</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if(auth()->user()->isAdmin()&&$trashedCategories->count() > 0)
            <div>
                <h2 class="text-2xl font-bold text-white mb-4">Корзина (удаленные категории)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($trashedCategories as $category)
                        <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg opacity-75">
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-400 mb-2">
                                    {{ $category->name }}
                                </h2>

                                @if($category->parent)
                                    <div class="text-gray-500 text-sm mb-1">
                                        Родитель: {{ $category->parent->name }}
                                    </div>
                                @endif

                                <div class="text-gray-500 text-sm mb-4">
                                    Товаров: {{ $category->products()->count() }}
                                    @if($category->children->count() > 0)
                                        <br>Подкатегорий: {{ $category->children->count() }}
                                    @endif
                                    @if($category->deleted_at)
                                        <br>Удалена: {{ $category->deleted_at->format('d.m.Y H:i') }}
                                    @endif
                                </div>

                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('categories.restore', $category->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Восстановить категорию «{{ $category->name }}»?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                                            Восстановить
                                        </button>
                                    </form>

                                    <form action="{{ route('categories.forceDestroy', $category->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('ВНИМАНИЕ! Категория «{{ $category->name }}» будет удалена навсегда. Это действие нельзя отменить. Продолжить?')">
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
            {{ $categories->links() }}
        </div>
    </div>
@endsection
