<!-- resources/views/layouts/partials/header.blade.php -->
<header class="bg-white shadow-xl sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Верхняя строка -->
        <div class="flex items-center justify-between h-20">
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}"
                   class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    👥 Пользователи
                </a>
            @endif

            <!-- Логотип -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-x-3 group">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-orange-500 to-pink-500 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg group-hover:shadow-xl transition-all">
                        E
                    </div>
                    <div>
                        <span
                            class="text-2xl font-black bg-gradient-to-r from-orange-600 to-pink-600 bg-clip-text text-transparent">Electro</span>
                        <span class="block text-[10px] font-bold text-gray-400 tracking-wider">MEGA STORE</span>
                    </div>
                </a>
            </div>

            <!-- Поиск -->
            <div class="flex-1 max-w-xl mx-10">
                <form action="{{ route('home') }}" method="GET">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            placeholder="🔍 What are you looking for?"
                            value="{{ request()->search }}"
                            class="w-full bg-gray-50 border-2 border-gray-100 focus:border-orange-300 rounded-2xl py-4 px-6 pl-14 text-sm transition-all outline-none focus:ring-4 focus:ring-orange-100">
                        <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <button type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-gradient-to-r from-orange-500 to-pink-500 text-white px-6 py-2 rounded-xl text-sm font-bold">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Иконки -->
            <div class="flex items-center gap-x-4">
                <!-- Избранное (заглушка) -->
                <a href="#" class="relative bg-gray-50 p-2.5 rounded-2xl hover:bg-orange-50 transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600 group-hover:text-orange-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364"/>
                    </svg>
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">12</span>
                </a>

                <!-- Корзина -->
                <a href="{{ route('basket.index') }}" class="relative bg-gray-50 p-2.5 rounded-2xl hover:bg-orange-50 transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600 group-hover:text-orange-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span id="cart-count"
                          class="absolute -top-1 -right-1 bg-gradient-to-r from-orange-500 to-pink-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">
                        @auth
                            {{ auth()->user()->activeBasket()?->totalItems() ?? 0 }}
                        @elseif(!auth()->user() && !empty(get_basket_for_session()))
                            {{  array_sum(get_basket_for_session()) }}
                        @else
                            0
                        @endauth
                    </span>
                </a>

                <!-- Auth Section -->
                <div class="flex items-center gap-x-3">
                    @guest
                        <a href="{{ route('login') }}"
                           class="flex items-center gap-x-2 bg-gray-50 hover:bg-orange-50 px-5 py-2.5 rounded-2xl transition-all group">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-gray-600 group-hover:text-orange-500" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7"/>
                            </svg>
                            <span class="hidden sm:block text-sm font-semibold text-gray-700 group-hover:text-orange-500">
                                Войти
                            </span>
                        </a>

                        <a href="{{ route('register') }}"
                           class="flex items-center gap-x-2 bg-black text-white hover:bg-zinc-800 px-6 py-2.5 rounded-2xl transition-all font-semibold text-sm">
                            Регистрация
                        </a>
                    @else
                        <div class="relative">
                            <button id="user-menu-button"
                                    onclick="toggleUserDropdown()"
                                    class="flex items-center gap-x-2 bg-gray-50 hover:bg-orange-50 px-5 py-2.5 rounded-2xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7"/>
                                </svg>
                                <span class="hidden sm:block text-sm font-semibold text-gray-700">
                                    {{ Auth::user()->name }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div id="user-dropdown"
                                 class="hidden absolute right-0 mt-3 w-64 bg-white rounded-3xl shadow-2xl border border-zinc-100 py-2 z-50 overflow-hidden">
                                <div class="px-6 py-4 border-b border-zinc-100">
                                    <p class="font-semibold text-black">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-zinc-500 mt-0.5">{{ Auth::user()->email }}</p>
                                    <p class="text-base font-semibold text-green-600 mt-0.5">
                                        Роль: {{ Auth::user()->getRole() }}
                                    </p>
                                </div>

                                <a href="{{ route('my-orders.index') }}"
                                   class="flex items-center gap-x-3 px-6 py-3.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    Мои заказы
                                </a>

                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.orders.index') }}"
                                       class="flex items-center gap-x-3 px-6 py-3.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        Управление заказами
                                    </a>
                                @endif

                                @if(Auth::user()->canManageProducts())
                                    <a href="{{ route('products.create') }}"
                                       class="flex items-center gap-x-3 px-6 py-3.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Добавить товар
                                    </a>
                                @endif

                                <div class="border-t border-zinc-100 mt-1"></div>

                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="flex items-center gap-x-3 px-6 py-3.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Выйти
                                </a>
                            </div>
                        </div>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleUserDropdown() {
        const dropdown = document.getElementById('user-dropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function (event) {
        const button = document.getElementById('user-menu-button');
        const dropdown = document.getElementById('user-dropdown');

        if (button && dropdown) {
            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        }
    });
</script>
