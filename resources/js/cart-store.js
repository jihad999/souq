import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        count: window.initialCartCount || 0,
        subtotal: parseFloat(window.initialCartSubtotal) || 0,
        items: (window.initialCartItems || []).map(item => ({
            ...item,
            price: parseFloat(item.price) || 0,
            lineTotal: parseFloat(item.lineTotal) || 0,
            quantity: parseInt(item.quantity) || 0,
        })),
        loading: false,

        async request(url, method = 'POST', body = {}) {
            this.loading = true;
            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(body),
                });

                const data = await response.json();

                if (data.success !== false) {
                    this.count = data.cartItemsCount;
                    this.subtotal = parseFloat(data.cartSubtotal) || 0;
                    this.items = (data.cartItems || []).map(item => ({
                        ...item,
                        price: parseFloat(item.price) || 0,
                        lineTotal: parseFloat(item.lineTotal) || 0,
                        quantity: parseInt(item.quantity) || 0,
                    }));
                }

                this.loading = false;
                return data;
            } catch (error) {
                this.loading = false;
                console.error('Cart error:', error);
                return { success: false, message: 'حدث خطأ، حاول مرة أخرى.' };
            }
        },

        async add(productId, quantity = 1) {
            const result = await this.request(`/cart/add/${productId}`, 'POST', { quantity });
            return result;
        },

        async updateQuantity(cartItemId, quantity) {
            return await this.request(`/cart/update/${cartItemId}`, 'PATCH', { quantity });
        },

        async remove(cartItemId) {
            return await this.request(`/cart/remove/${cartItemId}`, 'DELETE');
        },

        isInCart(productId) {
            return this.items.findIndex(item => item.product_id === productId) !== -1;
        },

        async clearCart() {
            return await this.request('/cart/clear', 'DELETE');
        },
    });
    Alpine.store('confirm', {
        open: false,
        message: '',
        onConfirm: null,

        show(message, callback) {
            this.message = message;
            this.onConfirm = callback;
            this.open = true;
        },

        confirmed() {
            if (this.onConfirm) this.onConfirm();
            this.open = false;
        },

        cancel() {
            this.open = false;
            this.onConfirm = null;
        },
    });
});