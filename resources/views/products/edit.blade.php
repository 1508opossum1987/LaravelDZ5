@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-white">Редактировать товар</h1>
                <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-300">
                    ← Назад к списку
                </a>
            </div>

            <div class="bg-gray-900 rounded-lg p-6">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-white mb-2">Название товара *</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $product->name) }}"
                               class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-white mb-2">Описание</label>
                        <textarea name="description"
                                  id="description"
                                  rows="5"
                                  class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-white mb-2">Цена *</label>
                        <input type="number"
                               name="price"
                               id="price"
                               step="0.01"
                               value="{{ old('price', $product->price) }}"
                               class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('price') border-red-500 @enderror"
                               required>
                        @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="discount_price" class="block text-white mb-2">Цена со скидкой</label>
                        <input type="number"
                               name="discount_price"
                               id="discount_price"
                               step="0.01"
                               value="{{ old('discount_price', $product->discount_price) }}"
                               class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('discount_price') border-red-500 @enderror">
                        @error('discount_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price_from" class="block text-white mb-2">Старая цена</label>
                        <input type="number"
                               name="price_from"
                               id="price_from"
                               step="0.01"
                               value="{{ old('price_from', $product->price_from) }}"
                               class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('price_from') border-red-500 @enderror">
                        @error('price_from')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block text-white mb-2">Категория *</label>
                        <select name="category_id"
                                id="category_id"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                                required>
                            <option value="">Выберите категорию</option>

                            @foreach($categories as $category)
                                <option
                                    value="{{ $category['id'] }}" {{ old('category_id', $product->category_id) == $category['id'] ? 'selected' : '' }}>
                                    {{ $category['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="brand_id" class="block text-white mb-2">Бренд *</label>
                        <select name="brand_id"
                                id="brand_id"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('brand_id') border-red-500 @enderror"
                                required>
                            <option value="">Выберите бренд</option>
                            @foreach($brands as $brand)
                                <option
                                    value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="country_id" class="block text-white mb-2">Страна *</label>
                        <select name="country_id"
                                id="country_id"
                                class="w-full bg-gray-800 text-white border border-gray-700 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 @error('country_id') border-red-500 @enderror"
                                required>
                            <option value="">Выберите страну</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{ $country->id }}" {{ old('country_id', $product->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="img_path" class="block text-white mb-2">Изображение товара</label>
                        @if($product->img_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->name }}"
                                     class="w-32 h-32 object-cover rounded">
                                <p class="text-gray-400 text-sm mt-1">Текущее изображение</p>
                            </div>
                        @endif
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
                                   {{ old('active', $product->active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 bg-gray-800 border-gray-700 rounded focus:ring-blue-500">
                            <span class="text-white">Активен</span>
                        </label>
                        <p class="text-gray-400 text-sm mt-1">Неактивные товары не отображаются на сайте</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                            Сохранить изменения
                        </button>
                        <a href="{{ route('products.index') }}"
                           class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
