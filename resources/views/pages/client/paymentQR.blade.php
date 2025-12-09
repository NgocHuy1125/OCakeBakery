@extends('layouts.client.master')

@section('title', 'Quét QR thanh toán')

@section('content')
@php
    $expiresAt = $payment['expires_at'] ?? null;
    $expireIso = $expiresAt ? $expiresAt->toIso8601String() : null;
@endphp
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="nav-link text-success">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="nav-link text-success">Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quét QR thanh toán</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Quét mã QR để thanh toán</h5>
                    <span class="badge bg-light text-success">
                        <i class="far fa-clock me-1"></i>
                        <span id="countdown" data-expire="{{ $expireIso }}">--:--</span>
                    </span>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center" style="min-height: 420px;">
                    <div class="w-100 text-center mb-3">
                        <div class="fw-semibold text-muted">Thanh toán cho đơn #{{ $order->order_code }}</div>
                        <div class="display-6 fw-bold text-success">{{ number_format($payment['amount'], 0, ',', '.') }} ₫</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center border rounded bg-white"
                         style="width: 320px; height: 320px;">
                        @if(!empty($payment['qr_url']))
                            <img src="{{ $payment['qr_url'] }}"
                                 alt="QR thanh toán SePay"
                                 style="max-width: 100%; max-height: 100%;">
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-qrcode fa-3x mb-2"></i>
                                <p class="mb-0">Không thể tạo QR. Vui lòng liên hệ hỗ trợ.</p>
                            </div>
                        @endif
                    </div>
                    <div class="mt-3 small text-muted text-center">
                        Vui lòng hoàn tất thanh toán trước khi hết hạn.
                    </div>
                    <div class="mt-4 w-100 d-flex justify-content-end gap-2">
                        <button id="cancelBtn" class="btn btn-outline-secondary fw-bold" type="button">
                            Huỷ đơn hàng
                        </button>
                        <button id="confirmPaidBtn" class="btn btn-success fw-bold" type="button">
                            Tôi đã thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2"><strong>Mã đơn:</strong> {{ $order->order_code }}</div>
                    <div class="mb-2"><strong>Khách hàng:</strong> {{ $order->customer_name }}</div>
                    <div class="mb-2"><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</div>
                    <div class="mb-2"><strong>Email đặt hàng:</strong> {{ $order->customer_email ?? '—' }}</div>
                    <div class="mb-2">
                        <strong>Địa chỉ nhận hàng:</strong><br>
                        {{ $order->address_line }}, {{ $order->ward_name }}, {{ $order->district_name }}
                    </div>
                    <div class="mb-2"><strong>Ghi chú:</strong> {{ $order->customer_note ?? 'Không có' }}</div>
                    <div class="mt-3 border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <span>{{ number_format($order->subtotal_amount, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển</span>
                            <span>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá</span>
                            <span>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3 fw-bold text-success">
                            <span>Tổng thanh toán</span>
                            <span>{{ number_format($order->grand_total, 0, ',', '.') }} ₫</span>
                        </div>
                        @if (!empty($payment['provider']) || !empty($payment['provider_label']))
                            <div class="small text-muted mt-2">
                                Nội dung chuyển khoản: {{ $payment['content'] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const statusUrl = '{{ route('payment.status', $order->order_code) }}';
    const orderUrl = '{{ route('profile.orders.show', $order->order_code) }}';
    const countdownEl = document.getElementById('countdown');
    const expireRaw = countdownEl?.dataset?.expire;
    let pollId = null;

    if (expireRaw) {
        const expiry = new Date(expireRaw);

        const intervalId = setInterval(() => {
            const diffMs = expiry.getTime() - Date.now();
            if (diffMs <= 0) {
                clearInterval(intervalId);
                countdownEl.textContent = '00:00';
                alert('Mã QR đã hết hạn. Vui lòng tạo lại đơn hàng hoặc liên hệ hỗ trợ.');
                return;
            }

            const totalSeconds = Math.floor(diffMs / 1000);
            const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
            const seconds = String(totalSeconds % 60).padStart(2, '0');
            countdownEl.textContent = `${minutes}:${seconds}`;
        }, 1000);

        // Cập nhật ngay lần đầu
        const initialDiff = expiry.getTime() - Date.now();
        if (initialDiff > 0) {
            const totalSeconds = Math.floor(initialDiff / 1000);
            const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
            const seconds = String(totalSeconds % 60).padStart(2, '0');
            countdownEl.textContent = `${minutes}:${seconds}`;
        }
    }

    const pollPayment = async () => {
        try {
            const res = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (!data.ok) return;

            if (data.payment_status === 'paid') {
                if (pollId) clearInterval(pollId);
                const redirectUrl = data.redirect || orderUrl;
                const goToOrder = () => {
                    window.location.href = redirectUrl;
                };

                if (window.Swal && typeof window.Swal.fire === 'function') {
                    window.Swal.fire({
                        icon: 'success',
                        title: 'Hoàn tất!',
                        text: 'Đơn hàng đã được thanh toán thành công.',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                    }).then(goToOrder);
                } else {
                    alert('Hoàn tất! Đơn hàng đã được thanh toán thành công.');
                    goToOrder();
                }
            }
        } catch (e) {
            // ignore transient errors
        }
    };

    pollId = setInterval(pollPayment, 4000);
    pollPayment();

    document.getElementById('cancelBtn')?.addEventListener('click', () => {
        window.location.href = '{{ route('cart.index') }}';
    });

    document.getElementById('confirmPaidBtn')?.addEventListener('click', () => {
        window.location.href = '{{ route('profile.orders.show', $order->order_code) }}';
    });
})();
</script>
@endsection
