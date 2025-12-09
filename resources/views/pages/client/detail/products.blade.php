@extends('layouts.client.master')

@section('title', $product->name . ' | Sản phẩm')

@php
  $primaryImage = $product->primary_image_url ?: asset('/images/product.jpg');
@endphp

@section('content')
<div class="product-detail-page py-5">
  <div class="container">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4 col-lg-12">
      <ol class="breadcrumb align-items-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-dark">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-dark">Sản phẩm</a></li>
        @if($product->primaryCategory)
          <li class="breadcrumb-item">
            <a href="{{ route('products.index', ['category' => $product->primaryCategory->slug]) }}" class="text-decoration-none text-dark">
              {{ $product->primaryCategory->name }}
            </a>
          </li>
        @endif
        <li class="breadcrumb-item active text-success" aria-current="page">{{ $product->name }}</li>
      </ol>
    </nav>

    <div class="row g-4">
      {{-- Hình ảnh --}}
      <div class="col-lg-6">
        <div class="ratio ratio-4x3 rounded-4 overflow-hidden border">
          <img src="{{ $primaryImage }}" alt="{{ $product->name }}" class="w-100 h-100 object-fit-cover">
        </div>
      </div>

      {{-- Thông tin sản phẩm --}}
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h1 class="h3 mb-3">{{ $product->name }}</h1>
            <div class="d-flex align-items-baseline gap-3 mb-3">
              <span class="fs-3 fw-bold text-success">{{ number_format($product->display_price, 0, ',', '.') }}₫</span>
              @if($product->original_price && $product->original_price > $product->display_price)
                <span class="text-muted text-decoration-line-through">{{ number_format($product->original_price, 0, ',', '.') }}₫</span>
              @endif
            </div>

            @if($product->short_description)
              <p class="text-muted">{{ $product->short_description }}</p>
            @endif

            <div class="mb-3">
              @if($product->total_stock > 0)
                <span class="badge bg-success-subtle text-success">Còn hàng</span>
              @else
                <span class="badge bg-danger">Tạm hết hàng</span>
              @endif
            </div>

            {{-- Số lượng --}}
            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="input-group" style="max-width: 150px;">
                <button class="btn btn-outline-secondary" type="button" id="qtyMinus">-</button>
                <input type="number" id="detailQuantity" class="form-control text-center" value="1" min="1">
                <button class="btn btn-outline-secondary" type="button" id="qtyPlus">+</button>
              </div>
            </div>

            {{-- Nút hành động --}}
            <div class="d-grid d-sm-flex gap-2">
              <button class="btn btn-success flex-fill" id="addToCartBtn" data-product-id="{{ $product->id }}">
                <i class="fa-solid fa-cart-plus me-1"></i> Thêm vào giỏ
              </button>
              <button class="btn btn-outline-success flex-fill">Mua ngay</button>
            </div>

            <div class="small text-muted mt-3">
              Mã sản phẩm: {{ $product->product_code ?? 'N/A' }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Mô tả --}}
    <div class="row mt-5 g-4">
      <div class="col-lg-12 bg-white p-3">
        <h3 class="fw-bold mb-3">Mô tả sản phẩm</h3>
        <p class="text-muted">{!! nl2br(e($product->description ?? 'Chưa có mô tả.')) !!}</p>
      </div>
    </div>

    @if($relatedProducts->count())
    <div class="mt-5" id="related-products">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">Sản phẩm cùng danh mục</h3>
        <span class="text-muted small">Hiển thị {{ $relatedProducts->perPage() }} sản phẩm / trang</span>
      </div>
      <div class="row g-4">
        @foreach($relatedProducts as $item)
          <div class="col-md-6 col-lg-3">
            <x-product.card :product="$item" />
          </div>
        @endforeach
      </div>

      @if($relatedProducts->hasPages())
        <div class="mt-3">
          {{ $relatedProducts->appends(request()->except('related_page'))->fragment('related-products')->links('pagination::bootstrap-5') }}
        </div>
      @endif
    </div>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const qty = document.getElementById('detailQuantity');
  const showToast = (payload = {}) => {
    if (typeof window.flashToast === 'function') {
      window.flashToast({ autohide: true, delay: 5000, ...payload });
    } else {
      alert(payload.message || payload.title || 'Đã xử lý xong.');
    }
  };
  const updateCartCount = (count) => {
    if (typeof count === 'undefined') return;
    document.querySelectorAll('[data-cart-count]').forEach((el) => {
      el.textContent = count;
      el.classList.toggle('d-none', parseInt(count, 10) <= 0);
    });
  };
  document.getElementById('qtyMinus')?.addEventListener('click', () => {
    qty.value = Math.max(1, parseInt(qty.value || '1', 10) - 1);
  });
  document.getElementById('qtyPlus')?.addEventListener('click', () => {
    qty.value = parseInt(qty.value || '1', 10) + 1;
  });

  document.getElementById('addToCartBtn').addEventListener('click', async () => {
    const productId = '{{ $product->id }}';
    const quantity = parseInt(qty.value || '1', 10);

    const res = await fetch('{{ route('cart.add') }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ product_id: productId, quantity }),
    });

    const data = await res.json();
    if (data.toast) {
      showToast(data.toast);
    } else if (data.ok) {
      showToast({ type: 'success', title: 'Thành công', message: data.message || 'Đã thêm vào giỏ hàng.' });
    } else {
      showToast({ type: 'error', title: 'Thêm thất bại', message: data.message || 'Không thể thêm sản phẩm.' });
    }
    if (typeof data.cart_count !== 'undefined') {
      updateCartCount(data.cart_count);
    }
  });
});
</script>
@endpush
