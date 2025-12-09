if (!window.__cartAddHandlerRegistered) {
    const cartAddUrl = window.routes?.cartAdd || '/cart/add';
    const csrfToken =
        window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const showToast = (payload = {}) => {
        if (typeof window.flashToast === 'function') {
            window.flashToast(payload);
            return;
        }

        // Fallback alert
        alert(payload.message || 'Đã xử lý xong.');
    };

    const updateCartCount = (count) => {
        document.querySelectorAll('[data-cart-count]').forEach((el) => {
            el.textContent = count;
            el.classList.toggle('d-none', parseInt(count, 10) <= 0);
        });
    };

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-add-to-cart]');
        if (!button) {
            return;
        }

        event.preventDefault();

        const productId = button.dataset.productId;
        const quantity = parseInt(button.dataset.quantity || '1', 10);

        if (!productId) {
            showToast({
                type: 'danger',
                title: 'Không tìm thấy sản phẩm',
                message: 'Không thể thêm sản phẩm này vào giỏ hàng.',
            });
            return;
        }

        button.classList.add('disabled');

        try {
            const response = await fetch(cartAddUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity,
                }),
            });

            const data = await response.json().catch(() => ({}));

            if (data.toast) {
                showToast(data.toast);
            }

            if (response.status === 401 && data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 800);
                return;
            }

            if (!response.ok || !data.ok) {
                return;
            }

            updateCartCount(data.cart_count ?? 0);

            if (window.location.pathname === '/cart') {
                window.location.reload();
            }
        } catch (error) {
            console.error(error);
            showToast({
                type: 'danger',
                title: 'Lỗi hệ thống',
                message: 'Không thể thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.',
            });
        } finally {
            button.classList.remove('disabled');
        }
    });

    window.__cartAddHandlerRegistered = true;
}
