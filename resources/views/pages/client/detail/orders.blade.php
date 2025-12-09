@extends('layouts.client.master')

@section('title', 'Chi tiết đơn ' . $order->order_code)

@section('content')
@php
    $paymentLabels = [
        'pending' => 'Chờ thanh toán',
        'processing' => 'Đang xử lý',
        'paid' => 'Đã thanh toán',
        'failed' => 'Thanh toán thất bại',
        'refunded' => 'Đã hoàn tiền',
    ];

    $paymentBadgeClasses = [
        'pending' => 'bg-warning text-dark',
        'processing' => 'bg-info text-dark',
        'paid' => 'bg-success',
        'failed' => 'bg-danger',
        'refunded' => 'bg-secondary',
    ];

    $fulfillmentLabels = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã hủy',
    ];

    $fulfillmentBadgeClasses = [
        'pending' => 'bg-secondary',
        'processing' => 'bg-info text-dark',
        'shipped' => 'bg-warning text-dark',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-dark',
    ];

    $sourceLabels = [
        'website' => 'Website',
        'facebook' => 'Facebook',
        'zalo' => 'Zalo',
        'store' => 'Tại cửa hàng',
    ];

    $orderedAt = optional($order->ordered_at)->format('d/m/Y H:i');
    $statusHistory = $order->statusHistory->sortBy('created_at');
    $latestTransaction = $order->transactions->first();
@endphp

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-success text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.orders') }}" class="text-success text-decoration-none">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đơn #{{ $order->order_code }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h4 class="fw-bold text-success mb-1">Đơn hàng #{{ $order->order_code }}</h4>
                            <div class="text-muted">Đặt lúc {{ $orderedAt ?? '---' }}</div>
                            <div class="text-muted">Nguồn: {{ $sourceLabels[$order->source_channel] ?? ucfirst($order->source_channel) }}</div>
                        </div>
                        <div class="text-md-end">
                            <span class="badge {{ $paymentBadgeClasses[$order->payment_status] ?? 'bg-secondary' }} me-2">
                                {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                            </span>
                            <span class="badge {{ $fulfillmentBadgeClasses[$order->fulfillment_status] ?? 'bg-success' }}">
                                {{ $fulfillmentLabels[$order->fulfillment_status] ?? ucfirst($order->fulfillment_status) }}
                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-uppercase text-muted mb-3">Thông tin giao hàng</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2"><strong>Người nhận:</strong> {{ $order->customer_name }}</li>
                                <li class="mb-2"><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</li>
                                <li class="mb-2"><strong>Email:</strong> {{ $order->customer_email ?? '---' }}</li>
                                <li class="mb-2">
                                    <strong>Địa chỉ:</strong> {{ $order->address_line }}, {{ $order->ward_name }}, {{ $order->district_name }}
                                </li>
                                <li class="mb-2"><strong>Ghi chú của khách:</strong> {{ $order->customer_note ?? 'Không có' }}</li>
                                @if($order->internal_note)
                                    <li class="mb-2"><strong>Ghi chú nội bộ:</strong> {{ $order->internal_note }}</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-uppercase text-muted mb-3">Thanh toán & Giao hàng</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2"><strong>Phương thức:</strong> {{ $order->payment_method_label }}</li>
                                <li class="mb-2"><strong>Đơn vị xử lý:</strong> {{ $order->payment_provider_label }}</li>
                                <li class="mb-2"><strong>Trạng thái thanh toán:</strong> {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}</li>
                                <li class="mb-2"><strong>Trạng thái giao hàng:</strong> {{ $fulfillmentLabels[$order->fulfillment_status] ?? ucfirst($order->fulfillment_status) }}</li>
                                <li class="mb-2"><strong>Tiền cọc:</strong> {{ $order->deposit_amount ? number_format($order->deposit_amount, 0, ',', '.') . ' đ' : 'Không' }}</li>
                                <li class="mb-2"><strong>Mã khuyến mãi:</strong> {{ $order->coupon?->coupon_code ?? 'Không áp dụng' }}</li>
                                @if($latestTransaction)
                                    <li>
                                        <strong>Giao dịch mới nhất:</strong>
                                        <div class="text-muted">
                                            {{ $latestTransaction->transaction_code ?? '---' }} · {{ number_format($latestTransaction->amount, 0, ',', '.') }} đ
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold text-uppercase text-muted">Danh sách sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th style="width: 12%" class="text-center">SL</th>
                                    <th style="width: 18%" class="text-end">Đơn giá</th>
                                    <th style="width: 20%" class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold">{{ $item->product_name_snapshot }}</div>
                                            <div class="text-muted small">{{ $item->variant_name_snapshot }}</div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->sale_price ?? $item->list_price, 0, ',', '.') }} đ</td>
                                        <td class="text-end pe-4">{{ number_format($item->line_total, 0, ',', '.') }} đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="ps-4" colspan="3">Tạm tính</td>
                                    <td class="text-end pe-4">{{ number_format($order->subtotal_amount, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td class="ps-4" colspan="3">Giảm giá</td>
                                    <td class="text-end pe-4 text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr>
                                    <td class="ps-4" colspan="3">Phí vận chuyển</td>
                                    <td class="text-end pe-4">{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-4" colspan="3">Tổng cộng</td>
                                    <td class="text-end pe-4 text-success">{{ number_format($order->grand_total, 0, ',', '.') }} đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold text-uppercase text-muted">Nhật ký trạng thái</h6>
                </div>
                <div class="card-body">
                    @if($statusHistory->isEmpty())
                        <p class="text-muted small mb-0">Đơn hàng chưa có cập nhật nào ngoài trạng thái hiện tại.</p>
                    @else
                        <ul class="list-unstyled mb-0">
                            @foreach($statusHistory as $history)
                                <li class="mb-3">
                                    <div class="fw-semibold">
                                        @if($history->status_type === 'payment')
                                            {{ $paymentLabels[$history->status_value] ?? ucfirst($history->status_value) }}
                                        @else
                                            {{ $fulfillmentLabels[$history->status_value] ?? ucfirst($history->status_value) }}
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        {{ optional($history->created_at)->format('d/m/Y H:i') }}
                                        @if($history->note)
                                            · {{ $history->note }}
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-success fw-semibold">
                        <i class="fas fa-shopping-bag me-1"></i> Tiếp tục mua sắm
                    </a>
                    <a href="{{ route('profile.orders') }}" class="btn btn-outline-success fw-semibold">
                        <i class="fas fa-receipt me-1"></i> Xem danh sách đơn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
