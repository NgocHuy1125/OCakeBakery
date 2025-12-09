@extends('layouts.client.master')

@section('title', 'Đơn hàng của tôi')

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
        'processing' => 'Đang chuẩn bị',
        'shipped' => 'Đang giao hàng',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã huỷ',
    ];

    $fulfillmentBadgeClasses = [
        'pending' => 'bg-secondary',
        'processing' => 'bg-info text-dark',
        'shipped' => 'bg-warning text-dark',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-dark',
    ];

    $filters = $filters ?? [
        'status' => request('status'),
        'search' => request('search'),
    ];

    $statusCounters = array_merge([
        'all' => $orders->total(),
        'pending' => 0,
        'processing' => 0,
        'shipped' => 0,
        'delivered' => 0,
        'cancelled' => 0,
    ], $statusCounters ?? []);

    $statusTabs = [
        '' => 'Tất cả',
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang chuẩn bị',
        'shipped' => 'Đang giao',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã huỷ',
    ];

    $summaryTiles = [
        [
            'label' => 'Đơn đang xử lý',
            'value' => ($statusCounters['pending'] ?? 0) + ($statusCounters['processing'] ?? 0),
            'icon' => 'fa-clipboard-list text-warning',
        ],
        [
            'label' => 'Đơn đang giao',
            'value' => $statusCounters['shipped'] ?? 0,
            'icon' => 'fa-shipping-fast text-info',
        ],
        [
            'label' => 'Đơn đã giao',
            'value' => $statusCounters['delivered'] ?? 0,
            'icon' => 'fa-check-circle text-success',
        ],
    ];
@endphp

<style>
    .order-card {
        border-radius: 16px;
        border: 0;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.06);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 26px rgba(0, 0, 0, 0.08);
    }
    .status-pill {
        padding: 10px 18px;
        border-radius: 30px;
        border: 1px solid #e0e0e0;
        background-color: #fff;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #1f2937;
        text-decoration: none;
        transition: all .2s;
    }
    .status-pill.active {
        border-color: #16a34a;
        background-color: #16a34a;
        color: #fff;
    }
    .status-pill .badge {
        font-size: 12px;
        padding: 4px 10px;
    }
    .status-pill:hover {
        border-color: #16a34a;
        color: #16a34a;
    }
    .status-pill.active .badge {
        background: rgba(255,255,255,0.2);
        color: #fff;
    }
    .mini-stat {
        border-radius: 14px;
        border: 0;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,.05);
        padding: 16px 20px;
    }
</style>

<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <p class="text-muted mb-1">Xin chào, {{ auth()->user()->fullname ?? auth()->user()->name }}</p>
            <h3 class="fw-bold mb-0">Đơn hàng của tôi</h3>
        </div>
        <a href="{{ route('profile.home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-angle-left me-1"></i> Quay lại hồ sơ
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="mini-stat h-100">
                <p class="text-muted small mb-1">Tổng số đơn</p>
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-receipt text-success"></i>
                    <h4 class="fw-bold mb-0">{{ $statusCounters['all'] ?? $orders->total() }}</h4>
                </div>
            </div>
        </div>
        @foreach($summaryTiles as $tile)
            <div class="col-md-3">
                <div class="mini-stat h-100">
                    <p class="text-muted small mb-1">{{ $tile['label'] }}</p>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas {{ $tile['icon'] }}"></i>
                        <h4 class="fw-bold mb-0">{{ $tile['value'] }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="card border-0 shadow-sm p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label small text-muted">Tìm kiếm đơn hàng</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" name="search" placeholder="Nhập mã đơn, sản phẩm hoặc ghi chú..." value="{{ $filters['search'] }}">
                </div>
            </div>
            <div class="col-lg-4">
                <label class="form-label small text-muted">Trạng thái giao nhận</label>
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($fulfillmentLabels as $key => $label)
                        <option value="{{ $key }}" {{ ($filters['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 text-lg-end">
                <button class="btn btn-success px-4 me-2">Lọc kết quả</button>
                <a href="{{ route('profile.orders') }}" class="btn btn-outline-secondary">Xóa lọc</a>
            </div>
        </div>
    </form>

    <div class="d-flex flex-wrap gap-2 mb-4">
        @foreach($statusTabs as $value => $label)
            @php
                $isActive = ($filters['status'] ?? '') === $value;
                $query = array_filter([
                    'status' => $value ?: null,
                    'search' => $filters['search'],
                ], fn ($val) => filled($val));
                $countKey = $value === '' ? 'all' : $value;
            @endphp
            <a href="{{ route('profile.orders', $query) }}" class="status-pill {{ $isActive ? 'active' : '' }}">
                <span>{{ $label }}</span>
                <span class="badge rounded-pill {{ $isActive ? 'bg-white text-dark' : 'bg-light text-dark' }}">{{ $statusCounters[$countKey] ?? 0 }}</span>
            </a>
        @endforeach
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5">
            <div class="card-body">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold">Bạn chưa có đơn hàng nào</h5>
                <p class="text-muted">Khi bạn đặt bánh tại Kim Loan, đơn sẽ xuất hiện tại đây để tiện theo dõi.</p>
                <a href="{{ route('products.index') }}" class="btn btn-success px-4">Bắt đầu mua sắm</a>
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-4">
            @foreach($orders as $order)
                @php
                    $orderedAt = optional($order->ordered_at)->format('d/m/Y H:i');
                    $firstItems = $order->items->take(3);
                    $remainingCount = max($order->items->count() - $firstItems->count(), 0);
                @endphp
                <div class="card order-card">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                            <div>
                                <h5 class="fw-bold text-success mb-1">Đơn #{{ $order->order_code }}</h5>
                                <div class="text-muted small">Đặt lúc {{ $orderedAt ?? '---' }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $paymentBadgeClasses[$order->payment_status] ?? 'bg-secondary' }} me-2">
                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                </span>
                                <span class="badge {{ $fulfillmentBadgeClasses[$order->fulfillment_status] ?? 'bg-secondary' }}">
                                    {{ $fulfillmentLabels[$order->fulfillment_status] ?? $order->fulfillment_status }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-light rounded-3 p-3 mb-3">
                            <h6 class="fw-semibold mb-3">Sản phẩm</h6>
                            <div class="d-flex flex-column gap-3">
                                @foreach($firstItems as $item)
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="fw-semibold">{{ $item->product_name_snapshot }}</div>
                                            <div class="text-muted small">{{ $item->variant_name_snapshot }} · x{{ $item->quantity }}</div>
                                        </div>
                                        <div class="fw-semibold">{{ number_format($item->line_total, 0, ',', '.') }} ₫</div>
                                    </div>
                                @endforeach
                                @if($remainingCount > 0)
                                    <div class="text-muted small">+ {{ $remainingCount }} sản phẩm khác</div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="text-muted small">
                                Thanh toán: <span class="fw-semibold text-dark">{{ $order->payment_method_label }}</span>
                            </div>
                            <div class="text-end">
                                <div class="text-muted small">Tổng thanh toán</div>
                                <div class="text-success h4 mb-0">{{ number_format($order->grand_total, 0, ',', '.') }} ₫</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3">
                        <div class="text-muted small">Cập nhật: {{ optional($order->updated_at)->format('d/m/Y H:i') ?? '---' }}</div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('profile.orders.show', $order->order_code) }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye me-1"></i> Xem chi tiết
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-shopping-bag me-1"></i> Mua thêm
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $orders->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
