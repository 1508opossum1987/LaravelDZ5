const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export function showToast(message, type = 'success') {
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
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
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

async function sendRequest(url, method, data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
    };

    if (data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        const result = await response.json();

        if (result.success) {
            if (result.message) {
                showToast(result.message, 'success');
            }
            if (result.redirect) {
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            }
            return result;
        } else {
            showToast(result.message || 'Произошла ошибка', 'error');
            return null;
        }
    } catch (error) {
        console.error('Fetch error:', error);
        showToast('Ошибка соединения', 'error');
        return null;
    }
}

export function initCartHandlers() {
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            const quantity = btn.dataset.quantity || 1;

            await sendRequest('/basket/add', 'POST', {
                items: [{ product_id: productId, quantity: parseInt(quantity) }]
            });
        });
    });

    const updateForms = document.querySelectorAll('.update-quantity-form');
    updateForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const url = form.action;
            const quantity = form.querySelector('input[name="quantity"]').value;

            await sendRequest(url, 'PUT', { quantity: parseInt(quantity) });
        });
    });

    const removeForms = document.querySelectorAll('.remove-item-form');
    removeForms.forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!confirm('Вы уверены, что хотите удалить товар из корзины?')) return;
            await sendRequest(form.action, 'DELETE');
        });
    });

    const clearBtn = document.getElementById('clear-basket-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            if (!confirm('Вы уверены, что хотите очистить всю корзину?')) return;
            await sendRequest('/basket/clear', 'POST');
        });
    }

    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', async (e) => {
            e.preventDefault();
            await sendRequest('/basket/checkout', 'POST');
        });
    }
}
