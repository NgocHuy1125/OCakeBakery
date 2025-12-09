<?php if (! $__env->hasRenderedOnce('186aa678-2a61-400e-9f27-1d5f20baffd6')): $__env->markAsRenderedOnce('186aa678-2a61-400e-9f27-1d5f20baffd6'); ?>
<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!token) {
        return;
    }

    const badges = Array.from(document.querySelectorAll('[data-notification-count]'));
    const setBadgeValue = (value) => {
        if (!badges.length) {
            return;
        }
        const safeValue = Math.max(0, value);
        badges.forEach((badgeEl) => {
            badgeEl.dataset.count = safeValue;
            badgeEl.textContent = safeValue;
            if (safeValue <= 0) {
                badgeEl.classList.add('d-none');
            } else {
                badgeEl.classList.remove('d-none');
            }
        });
    };

    const decrementBadge = () => {
        if (!badges.length) {
            return;
        }
        const current = badges.reduce((carry, badgeEl) => {
            const value = parseInt(badgeEl.dataset.count ?? badgeEl.textContent, 10) || 0;
            return Math.max(carry, value);
        }, 0);
        if (current > 0) {
            setBadgeValue(current - 1);
        }
    };

    const markItemAsRead = (item, adjustBadge = true) => {
        if (!item || item.dataset.marked === '1') {
            return;
        }
        const dot = item.querySelector('.notification-dot');
        if (dot) {
            dot.classList.remove('text-primary');
            dot.classList.add('text-secondary');
        }
        item.classList.add('notification-item--read');
        item.dataset.marked = '1';
        if (adjustBadge) {
            decrementBadge();
        }
    };

    const postMarkRequest = (url, useKeepAlive = false) => {
        if (!url) {
            return Promise.resolve();
        }

        if (useKeepAlive && navigator.sendBeacon) {
            try {
                const data = new FormData();
                data.append('_token', token);
                navigator.sendBeacon(url, data);
                return Promise.resolve();
            } catch (err) {
                // Fallback to fetch below
            }
        }

        const body = new URLSearchParams();
        body.append('_token', token);

        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body,
            keepalive: useKeepAlive,
        }).catch(() => {});
    };

    document.querySelectorAll('.notification-item[data-mark-url]').forEach((item) => {
        item.addEventListener('click', (event) => {
            const url = item.dataset.markUrl;
            if (!url || item.dataset.marked === '1') {
                return;
            }

            const targetHref = item.getAttribute('href');
            const navigateAfter = targetHref && targetHref !== '#' && !targetHref.startsWith('javascript');

            if (navigateAfter) {
                event.preventDefault();
            }

            markItemAsRead(item);

            postMarkRequest(url, true).finally(() => {
                if (navigateAfter) {
                    window.location.href = targetHref;
                }
            });
        });
    });

    const markAllButtons = Array.from(document.querySelectorAll('[data-mark-all-url]'));
    if (markAllButtons.length) {
        markAllButtons.forEach((markAllBtn) => {
            markAllBtn.addEventListener('click', (event) => {
                event.preventDefault();
                const url = markAllBtn.dataset.markAllUrl;
                if (!url) {
                    return;
                }

                markAllButtons.forEach((btn) => (btn.disabled = true));

                document.querySelectorAll('.notification-item[data-mark-url]').forEach((item) => markItemAsRead(item, false));
                setBadgeValue(0);
                postMarkRequest(url).finally(() => {
                    markAllButtons.forEach((btn) => (btn.disabled = false));
                });
            });
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/partials/notifications-script.blade.php ENDPATH**/ ?>