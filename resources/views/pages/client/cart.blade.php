@extends('layouts.client.master')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumbs" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a class="nav-link" href="{{ url('/') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active text-success" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Sản phẩm đã chọn</h5>
                </div>
                <div class="card-body p-0">
                    @if($items->isEmpty())
                        <div class="p-4 text-center text-muted">
                            <p class="mb-2">Giỏ hàng của bạn đang trống.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-success">Tiếp tục mua sắm</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Sản phẩm</th>
                                        <th class="text-center" style="width: 150px;">Số lượng</th>
                                        <th class="text-end">Đơn giá</th>
                                        <th class="text-end" style="width: 150px;">Thành tiền</th>
                                        <th class="text-end pe-4">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        @php
                                            $unitPrice = $item->product?->sale_price ?? $item->product?->listed_price ?? 0;
                                            $lineTotal = $unitPrice * $item->quantity;
                                        @endphp
                                        <tr data-cart-item="{{ $item->id }}"
                                            data-update-url="{{ route('cart.items.update', $item) }}"
                                            data-delete-url="{{ route('cart.items.destroy', $item) }}">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ $item->product?->primary_image_url ?? asset('images/product.jpg') }}"
                                                         alt="{{ $item->product->name ?? 'Sản phẩm' }}"
                                                         class="rounded"
                                                         style="width: 64px; height: 64px; object-fit: cover;">
                                                    <div>
                                                        <div class="fw-semibold">{{ $item->product->name ?? 'Sản phẩm' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="input-group input-group-sm justify-content-center">
                                                    <button class="btn btn-outline-secondary cart-qty-btn" type="button" data-change="-1">-</button>
                                                    <input type="text" class="form-control text-center cart-qty-input"
                                                           value="{{ $item->quantity }}" readonly>
                                                    <button class="btn btn-outline-secondary cart-qty-btn" type="button" data-change="1">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                {{ number_format($unitPrice, 0, ',', '.') }} đ
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="fw-semibold cart-line-total">{{ number_format($lineTotal, 0, ',', '.') }} đ</div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-danger cart-remove-btn">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @if($items->isNotEmpty())
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <button class="btn btn-outline-secondary btn-sm cart-clear-btn" data-url="{{ route('cart.clear') }}">
                            Xóa toàn bộ giỏ hàng
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">Tiếp tục mua sắm</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm position-sticky top-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Tổng thanh toán</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span>Tạm tính</span>
                            <strong id="cartSubtotal">{{ number_format($totals['subtotal'], 0, ',', '.') }} đ</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span>Phí vận chuyển</span>
                            <strong id="cartShipping">{{ number_format($totals['shipping'], 0, ',', '.') }} đ</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <span>Giảm giá</span>
                            <strong id="cartDiscount">-{{ number_format($totals['discount'], 0, ',', '.') }} đ</strong>
                        </li>
                        <li class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="fw-bold">Thành tiền</span>
                            <span class="fs-5 fw-bold text-success" id="cartGrandTotal">
                                {{ number_format($totals['grand_total'], 0, ',', '.') }} đ
                            </span>
                        </li>
                    </ul>

                    @auth
                        <a href="{{ route('checkout.show') }}" class="btn btn-success w-100 mb-2"
                           @if($items->isEmpty()) disabled @endif>
                            Tiến hành đặt hàng
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-success w-100 mb-2">
                            Đăng nhập để đặt hàng
                        </a>
                    @endauth

                    <p class="small text-muted mb-0">
                        Miễn phí giao hàng cho hoá đơn từ 100.000đ
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = '{{ csrf_token() }}';
    const confirmAction = (message) => {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            return window.Swal.fire({
                title: 'X\u00e1c nh\u1eadn',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ti\u1ebfp t\u1ee5c',
                cancelButtonText: 'Hu\u1ef7',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
            }).then(result => result.isConfirmed);
        }
        return Promise.resolve(window.confirm(message));
    };

    const handleAuthRedirect = (res, data) => {
        if (res.status === 401 && data?.redirect) {
            if (typeof window.flashToast === 'function') {
                window.flashToast({
                    type: 'warning',
                    title: 'Đăng nhập để tiếp tục',
                    message: 'Vui lòng đăng nhập để quản lý giỏ hàng.',
                });
            } else {
                alert('Vui lòng đăng nhập để quản lý giỏ hàng.');
            }
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 800);
            return true;
        }
        return false;
    };

    function updateSummary(totals) {
        if (!totals) return;
        document.getElementById('cartSubtotal').textContent = new Intl.NumberFormat('vi-VN').format(totals.subtotal) + ' đ';
        document.getElementById('cartShipping').textContent = new Intl.NumberFormat('vi-VN').format(totals.shipping) + ' đ';
        document.getElementById('cartDiscount').textContent = '-' + new Intl.NumberFormat('vi-VN').format(totals.discount) + ' đ';
        document.getElementById('cartGrandTotal').textContent = new Intl.NumberFormat('vi-VN').format(totals.grand_total) + ' đ';
    }

    function handleQuantityChange(row, delta) {
        const qtyInput = row.querySelector('.cart-qty-input');
        const currentQty = parseInt(qtyInput.value, 10);
        const newQty = currentQty + delta;
        if (newQty < 1) return;

        fetch(row.dataset.updateUrl, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quantity: newQty }),
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (handleAuthRedirect(res, data)) return;
            if (!data.ok) {
                alert(data.message || 'Không thể cập nhật số lượng.');
                return;
            }

            qtyInput.value = newQty;
            const lineTotal = row.querySelector('.cart-line-total');
            lineTotal.textContent = new Intl.NumberFormat('vi-VN').format(data.item_total) + ' đ';
            updateSummary(data.totals);
        })
        .catch(() => alert('Có lỗi xảy ra, vui lòng thử lại.'));
    }

    async function handleRemove(row) {
        const ok = await confirmAction('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?');
        if (!ok) return;

        fetch(row.dataset.deleteUrl, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (handleAuthRedirect(res, data)) return;
            if (!data.ok) {
                alert(data.message || 'Không thể xóa sản phẩm.');
                return;
            }

            row.remove();
            updateSummary(data.totals);

            if (document.querySelectorAll('[data-cart-item]').length === 0) {
                window.location.reload();
            }
        })
        .catch(() => alert('Có lỗi xảy ra, vui lòng thử lại.'));
    }

    async function handleClear(button) {
        const ok = await confirmAction('Bạn muốn xóa toàn bộ sản phẩm trong giỏ hàng?');
        if (!ok) return;

        fetch(button.dataset.url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
        })
        .then(async res => {
            const data = await res.json().catch(() => ({}));
            if (handleAuthRedirect(res, data)) return;
            if (!data.ok) {
                alert(data.message || 'Không thể xóa giỏ hàng.');
                return;
            }
            window.location.reload();
        })
        .catch(() => alert('Có lỗi xảy ra, vui lòng thử lại.'));
    }

    document.querySelectorAll('.cart-qty-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('[data-cart-item]');
            const delta = parseInt(btn.dataset.change, 10);
            handleQuantityChange(row, delta);
        });
    });

    document.querySelectorAll('.cart-remove-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const row = btn.closest('[data-cart-item]');
            handleRemove(row);
        });
    });

    const clearBtn = document.querySelector('.cart-clear-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => handleClear(clearBtn));
    }
});
</script>
@endpush
