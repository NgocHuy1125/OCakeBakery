@extends('layouts.admin.master')

@section('title', 'Chi tiết đơn hàng #' . $order->order_code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">Chi tiết đơn hàng #{{ $order->order_code }}</h4>
  <div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">
      <i class="fas fa-angle-left me-1"></i> Quay lại
    </a>
    <button onclick="window.print()" class="btn btn-primary">
      <i class="bx bx-printer"></i> In đơn hàng
    </button>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <h6 class="fw-bold mb-2">Thông tin khách hàng</h6>
        <p>{{ $order->customer_name }}<br>
           {{ $order->customer_phone }}<br>
           {{ $order->address_line }}</p>
      </div>
      <div class="col-md-6 text-md-end">
        <h6 class="fw-bold mb-2">Trạng thái đơn hàng</h6>
        <span class="badge bg-info">{{ ucfirst($order->fulfillment_status) }}</span>
        <p class="mt-2">Ngày đặt: {{ $order->ordered_at?->format('d/m/Y H:i') }}</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>
              @if($item->product)
                <div class="d-flex align-items-center">
                  <img src="{{ $item->product->primary_image_url ?? asset('images/no-image.png') }}"
                      alt="{{ $item->product->name }}" width="60" height="60"
                      class="rounded border object-fit-cover me-2"
                      onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                  <div>
                    <div class="fw-semibold">{{ $item->product->name }}</div>
                    <small class="text-muted">Mã: {{ $item->product->product_code }}</small>
                  </div>
                </div>
              @else
                <span class="text-muted">Sản phẩm đã bị xoá</span>
              @endif
            </td>
            <td class="text-center">{{ $item->quantity }}</td>
            @php
              $unitPrice = $item->price ?? ($item->product->sale_price ?? $item->product->listed_price);
              $total = $unitPrice * $item->quantity;
            @endphp
            <td>{{ number_format($unitPrice, 0, ',', '.') }} ₫</td>
            <td>{{ number_format($total, 0, ',', '.') }} ₫</td>
          </tr>
          @endforeach
        </tbody>

      </table>
    </div>

    <div class="text-end mt-3">
      <p class="mb-1">Tạm tính: <strong>{{ number_format($order->subtotal_amount, 0, ',', '.') }} ₫</strong></p>
      <p class="mb-1">Giảm giá: <strong>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</strong></p>
      <p class="mb-1">Phí giao hàng: <strong>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</strong></p>
      <h5 class="mt-3 fw-bold">Tổng cộng: {{ number_format($order->grand_total, 0, ',', '.') }} ₫</h5>
    </div>
  </div>
</div>
@endsection
