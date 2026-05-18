@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-white">Создать бренд</h1>
                <a href="{{ route('brands.index') }}" class="text-gray-400 hover:text-gray-300">
                    ← Назад к списку
                </a>
            </div>

            <div class="bg-gray-900 rounded-lg p-6">
                <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">>
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-white mb-2">Название бренда *</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="img_path" class="block text-white mb-2">Изображение товара</label>
                        <input type="file"
                               name="img_path"
                               id="img_path"
                               accept="image/*"
                               class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('img_path') border-red-500 @enderror">
                        @error('img_path')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                   name="active"
                                   value="1"
                                   {{ old('active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-800 border-gray-700 rounded focus:ring-blue-500">
                            <span class="text-white">Активен</span>
                        </label>
                        <p class="text-gray-400 text-sm mt-1">Неактивные бренды не отображаются на сайте</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            Создать бренд
                        </button>
                        <a href="{{ route('brands.index') }}"
                           class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
