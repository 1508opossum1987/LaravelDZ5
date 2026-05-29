import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

console.log('echo.js загружен');

window.Pusher = Pusher;

function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bgColor = type === 'success' ? '#10B981' : (type === 'error' ? '#EF4444' : '#3B82F6');
    toast.style.cssText = `
        background: ${bgColor};
        color: white;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        font-size: 14px;
        font-weight: 500;
        animation: slideInRight 0.3s ease;
        cursor: pointer;
        min-width: 250px;
        max-width: 350px;
    `;
    toast.textContent = message;

    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    if (!document.querySelector('#toast-style')) {
        style.id = 'toast-style';
        document.head.appendChild(style);
    }

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);

    toast.onclick = () => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    };
}

export function initEcho(userId) {
    console.log('initEcho вызван, userId:', userId);

    if (!userId) {
        console.warn('Echo: userId не передан');
        return null;
    }

    console.log('Начинаем создавать Echo с настройками:', {
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    });

    try {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: import.meta.env.VITE_REVERB_HOST,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
            forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'http') === 'https',
            enabledTransports: ['ws', 'wss'],
        });

        console.log('Echo создан успешно');

        const channel = window.Echo.private(`basket.${userId}`);
        console.log('Подписались на канал basket.' + userId);

        channel.listen('BasketUpdateEvent', (event) => {
            console.log('BasketUpdateEvent получен:', event);

            function updateCartCount() {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    cartCount.textContent = event.total_items;
                    console.log('Счётчик обновлён:', event.total_items);
                } else {
                    setTimeout(updateCartCount, 100);
                }
            }

            updateCartCount();
            showToast('Корзина обновлена!', 'success');
        });

        channel.listen('BasketCompletedEvent', (event) => {
            console.log('BasketCompletedEvent получен:', event);
            showToast('Заказ успешно оформлен!', 'success');
        });

        const isAdmin = document.querySelector('meta[name="is-admin"]')?.getAttribute('content') === '1';
        console.log('isAdmin:', isAdmin);

        if (isAdmin) {
            console.log('Подписываемся на admin.notifications');
            const adminChannel = window.Echo.private('admin.notifications');

            adminChannel.listen('OrderCreatedEvent', (notification) => {
                console.log('Новое уведомление:', notification);
                showToast(notification.message, 'info');

                const event = new CustomEvent('new-notification', { detail: notification });
                window.dispatchEvent(event);
            });

            console.log('Подписались на канал admin.notifications');
        }

        return channel;
    } catch (error) {
        console.error('Ошибка при создании Echo:', error);
    }
}
