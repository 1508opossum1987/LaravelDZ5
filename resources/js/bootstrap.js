console.log('bootstrap.js загружен - начало');

import { initEcho } from './echo.js';
import { initCartHandlers, showToast } from './cart.js';

console.log('bootstrap.js загружен - после импорта');

// Выносим функции в глобальный объект для использования в HTML
window.showToast = showToast;

document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM загружен');

    const userIdElement = document.querySelector('meta[name="user-id"]');
    const userId = userIdElement ? userIdElement.getAttribute('content') : null;

    console.log('userId из meta:', userId);

    if (userId) {
        initEcho(userId);
    } else {
        console.log('Echo: пользователь не авторизован');
    }

    // Инициализируем обработчики корзины
    initCartHandlers();
});

console.log('bootstrap.js загружен - конец файла');
