@props(['product'])

<div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-cyan-200">

    <!-- Бейджи -->
    <div class="absolute top-3 left-3 z-10 flex flex-col gap-2">
        @if($product->discount_price && $product->discount_price < $product->price)
            <span class="bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
            </span>
        @endif

        @if($product->price_from && $product->price_from > 0)
            <span class="bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg">
                🔥 Хит
            </span>
        @endif
    </div>

    <!-- Избранное -->
    <button class="absolute top-3 right-3 z-10 bg-white/80 backdrop-blur-sm p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 hover:bg-red-50 group/fav">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 group-hover/fav:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364" />
        </svg>
    </button>

    <!-- Изображение -->
    <a href="{{ route('products.show', $product) }}" class="block overflow-hidden bg-gray-50">
        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-contain p-6 transition-transform duration-500 group-hover:scale-110">
    </a>

    <!-- Контент -->
    <div class="p-5 pt-2">
        <!-- Бренд и категория -->
        <div class="flex items-center justify-between text-xs text-gray-400 mb-2">
            <span class="hover:text-cyan-500 transition">
                {{ $product->brand->name ?? 'Без бренда' }}
            </span>
            <span class="bg-gray-100 px-2 py-1 rounded-full">
                {{ $product->category->name ?? 'Категория' }}
            </span>
        </div>

        <!-- Название -->
        <a href="{{ route('products.show', $product) }}" class="block">
            <h3 class="font-semibold text-gray-800 hover:text-cyan-600 transition line-clamp-2 min-h-[56px]">
                {{ $product->name }}
            </h3>
        </a>

        <!-- Страна -->
        <div class="flex items-center gap-1 text-xs text-gray-400 mt-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ $product->country->name ?? 'Страна не указана' }}</span>
        </div>

        <!-- Рейтинг (заглушка) -->
        <div class="flex items-center gap-2 mt-3">
            <div class="flex items-center gap-0.5">
                <span class="text-yellow-400 text-sm">★★★★★</span>
            </div>
            <span class="text-xs text-gray-400">(4.8)</span>
        </div>

        <!-- Цена -->
        <div class="mt-4 flex items-baseline gap-2">
            @if($product->discount_price && $product->discount_price < $product->price)
                <span class="text-2xl font-bold text-gray-900">
                    {{ number_format($product->discount_price, 0, ',', ' ') }} ₽
                </span>
                <span class="text-sm text-gray-400 line-through">
                    {{ number_format($product->price, 0, ',', ' ') }} ₽
                </span>
            @else
                <span class="text-2xl font-bold text-gray-900">
                    {{ number_format($product->price, 0, ',', ' ') }} ₽
                </span>
            @endif

            @if($product->price_from && $product->price_from > 0)
                <span class="text-xs text-gray-400">
                    от {{ number_format($product->price_from, 0, ',', ' ') }} ₽
                </span>
            @endif
        </div>

        <!-- Кнопки -->
        <div class="mt-5 flex items-center gap-2">
            <button type="button"
                    class="add-to-cart-btn w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white font-semibold py-2.5 rounded-xl hover:from-cyan-600 hover:to-blue-600 transition-all duration-300 text-sm"
                    data-product-id="{{ $product->id }}"
                    data-quantity="1">
                В корзину
            </button>
            <button class="w-10 h-10 bg-gray-100 rounded-xl hover:bg-cyan-100 transition-all duration-300 flex items-center justify-center group/quick">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 group-hover/quick:text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </button>
        </div>
    </div>
</div>
