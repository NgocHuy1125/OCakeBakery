@extends('layouts.client.master')

@section('title', 'Thanh toán')

@section('content')
<div class="container py-5">
  <nav aria-label="breadcrumbs" class="mb-4">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Trang chủ</a></li>
      <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Giỏ hàng</a></li>
      <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
    </ol>
  </nav>

  <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
    @csrf
    <input type="hidden" name="address_id" id="checkoutSelectedAddress" value="{{ $addresses->first()->id ?? '' }}">
    <div class="row g-4">
      <div class="col-lg-7">
        {{-- 🏠 Thông tin giao hàng --}}
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Thông tin giao hàng</h5>
            <a href="{{ route('profile.addresses') }}" class="btn btn-sm btn-success">
              <i class="fas fa-plus me-1"></i> Thêm địa chỉ
            </a>
          </div>
          <div class="card-body">
            @if($addresses->isEmpty())
              <div class="alert alert-info border-0 bg-info-subtle text-info">
                Bạn chưa có địa chỉ giao hàng. Vui lòng thêm mới để tiếp tục đặt hàng.
              </div>
            @else
              <div class="vstack gap-3">
                @foreach($addresses as $address)
                  <div class="form-check border rounded p-3 @if($address->is_default) border-success @endif">
                    <input class="form-check-input" type="radio" name="checkout_address"
                           id="address-{{ $address->id }}" value="{{ $address->id }}"
                           @checked($loop->first)>
                    <label class="form-check-label ms-2" for="address-{{ $address->id }}">
                      <div class="fw-semibold d-flex align-items-center gap-2">
                        {{ $address->receiver_name }}
                        <span class="badge bg-light text-dark">{{ $address->receiver_phone }}</span>
                        @if($address->is_default)
                          <span class="badge bg-success">Mặc định</span>
                        @endif
                      </div>
                      <div class="text-muted small">
                        {{ $address->address_line }}, {{ $address->ward_name }}, {{ $address->district_name }}
                      </div>
                    </label>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        {{-- 💳 Phương thức thanh toán --}}
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0">Phương thức thanh toán</h5>
          </div>
          <div class="card-body">
            <div class="vstack gap-3">
              <div class="form-check border rounded p-3 d-flex align-items-center gap-3">
                <input class="form-check-input me-2" type="radio" name="checkout_payment"
                       id="payment-cod" value="cod" checked>
                <label class="form-check-label d-flex align-items-center gap-2" for="payment-cod">
                  <img src="{{ asset('images/cod.png') }}" alt="COD" style="width:36px;height:36px;">
                  <div>
                    <div class="fw-semibold">Thanh toán khi nhận hàng (COD)</div>
                    <div class="text-muted small">Thanh toán trực tiếp khi giao hàng.</div>
                  </div>
                </label>
              </div>

              <div class="form-check border rounded p-3 d-flex align-items-center gap-3">
                <input class="form-check-input me-2" type="radio" name="checkout_payment"
                       id="payment-qr" value="qr">
                <label class="form-check-label d-flex align-items-center gap-2" for="payment-qr">
                  <img src="{{ asset('images/qrcode.png') }}" alt="QR đa năng" style="width:36px;height:36px;">
                  <div>
                    <div class="fw-semibold">Thanh toán bằng QR đa năng</div>
                    <div class="text-muted small">Quét mã QR qua ngân hàng hoặc ví điện tử.</div>
                  </div>
                </label>
              </div>
            </div>

            <div class="mt-3">
              <label for="checkout-note" class="form-label">Ghi chú cho đơn hàng</label>
              <textarea id="checkout-note" name="customer_note" class="form-control" rows="3"
                        placeholder="Ví dụ: giao trong giờ hành chính, liên hệ trước khi giao..."></textarea>
            </div>
          </div>
        </div>
      </div>

      {{-- 🛒 Đơn hàng --}}
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0">Đơn hàng của bạn</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush mb-3">
              @foreach($cartItems as $item)
                @php
                  $basePrice = $item->variant?->price ?? $item->product?->listed_price ?? 0;
                  $salePrice = $item->variant?->sale_price ?? $item->product?->sale_price;
                  $lineTotal = ($salePrice ?? $basePrice) * $item->quantity;
                @endphp
                <li class="list-group-item px-0 d-flex justify-content-between">
                  <div>
                    <div class="fw-semibold">{{ $item->product->name ?? 'Sản phẩm' }}</div>
                    <div class="small text-muted">x{{ $item->quantity }}</div>
                  </div>
                  <div class="fw-semibold">{{ number_format($lineTotal, 0, ',', '.') }} đ</div>
                </li>
              @endforeach
            </ul>

            <div class="d-flex justify-content-between mb-2"><span>Tạm tính</span><span>{{ number_format($totals['subtotal'], 0, ',', '.') }} đ</span></div>
            <div class="d-flex justify-content-between mb-2"><span>Phí vận chuyển</span><span>{{ number_format($totals['shipping'], 0, ',', '.') }} đ</span></div>
            <div class="d-flex justify-content-between mb-2"><span>Giảm giá</span><span>-{{ number_format($totals['discount'], 0, ',', '.') }} đ</span></div>
            <div class="d-flex justify-content-between border-top pt-3"><span class="fw-bold">Thành tiền</span><span class="fw-bold text-success">{{ number_format($totals['grand_total'], 0, ',', '.') }} đ</span></div>
          </div>
          <div class="card-footer bg-white py-3">
            <button type="submit" class="btn btn-success w-100" @if($addresses->isEmpty()) disabled @endif>Xác nhận đặt hàng</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
  const addressField = document.getElementById("checkoutSelectedAddress");

  // ✅ Bắt sự kiện chọn địa chỉ
  document.querySelectorAll("input[name='checkout_address']").forEach(radio => {
    radio.addEventListener("change", () => {
      addressField.value = radio.value;
    });
  });

  // ✅ Bắt sự kiện chọn phương thức thanh toán
  document.querySelectorAll("input[name='checkout_payment']").forEach(radio => {
    radio.addEventListener("change", e => {
      console.log("Phương thức đã chọn:", e.target.value);
    });
  });

  // ✅ Khi submit form, log toàn bộ dữ liệu gửi đi
  document.getElementById('checkoutForm').addEventListener('submit', e => {
    const checkedPayment = document.querySelector("input[name='checkout_payment']:checked");
    console.log("🧾 Gửi form với checkout_payment =", checkedPayment ? checkedPayment.value : '(none)');
  });
});
</script>
@endpush
