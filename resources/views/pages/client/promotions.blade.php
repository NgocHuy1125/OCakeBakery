@extends('layouts.client.master')

@section('title', 'Chương trình khuyến mãi')

@section('content')
<div class="promotions-page">

  <!-- Hero -->
  <section class="hero-banner mb-5 position-relative text-center text-white rounded-4 overflow-hidden hero-bg-bakery">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="container-fluid hero-content position-relative p-5">
      <h1 class="fw-bold display-5 mb-3 text-uppercase">Chương trình khuyến mãi</h1>
      <p class="lead mb-4 col-md-8 col-12 mx-auto">
        Danh sách và thông tin các chương trình khuyến mãi tại <b>Tiệm bánh Kim Loan</b> — áp dụng khi mua Online & Offline.
      </p>
      <a href="#promotions" class="btn btn-light btn-lg px-3">
        Khám phá ngay <i class="fas fa-arrow-up-right-from-square ms-1"></i>
      </a>
    </div>
  </section>

  <!-- Danh sách chương trình khuyến mãi -->
  <section id="promotions" class="pb-5">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2 w-max-content mx-auto">Ưu đãi đang diễn ra</h2>
        <p class="text-muted">Nhanh tay chọn bánh, nhận ưu đãi hấp dẫn trong hôm nay!</p>
      </div>

      <div class="row g-3">
        @forelse ($promotions as $promo)
          <div class="col-md-4 col-12">
            <div class="card border-0 shadow-sm h-100">
              <a href="#" data-bs-toggle="modal" data-bs-target="#promoModal-{{ $promo->id }}">
                <img src="{{ $promo->banner_url ?? asset('/images/promotions.jpg') }}"
                     class="card-img-top" alt="{{ $promo->name }}">
              </a>
              <div class="card-body">
                <h5 class="fw-bold text-success text-uppercase max-line-2 text-justify">
                  {{ $promo->name }}
                </h5>
                <p class="text-muted small mb-3 max-line-2 text-justify">
                  {{ Str::limit($promo->description ?? 'Không có mô tả.', 80) }}
                </p>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ optional($promo->start_date)->format('d/m/Y') }} - {{ optional($promo->end_date)->format('d/m/Y') }}
                  </small>
                  <a href="#" data-bs-toggle="modal" data-bs-target="#promoModal-{{ $promo->id }}" class="btn btn-outline-success btn-sm">
                    Xem chi tiết <i class="fas fa-angle-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal chi tiết khuyến mãi -->
          <div class="modal fade" id="promoModal-{{ $promo->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header bg-success text-white">
                  <h5 class="modal-title">{{ $promo->name }}</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-6">
                      <img src="{{ $promo->banner_url ?? asset('/images/promotions.jpg') }}"
                           class="img-fluid rounded shadow-sm" alt="{{ $promo->name }}">
                    </div>
                    <div class="col-md-6">
                      <p class="text-muted small mb-2">
                        <i class="fas fa-clock me-1"></i> Hiệu lực:
                        {{ optional($promo->start_date)->format('d/m/Y') }} - {{ optional($promo->end_date)->format('d/m/Y') }}
                      </p>
                      <p>{{ $promo->description ?? 'Chưa có mô tả chi tiết.' }}</p>
                      <ul class="list-unstyled small text-muted">
                        <li><strong>Slug:</strong> {{ $promo->slug }}</li>
                        <li><strong>Loại:</strong> {{ $promo->discount_type === 'percentage' ? 'Giảm %' : 'Giảm tiền cố định' }}</li>
                        <li><strong>Giá trị:</strong> {{ number_format($promo->discount_value, 0, ',', '.') }}{{ $promo->discount_type === 'percentage' ? '%' : '₫' }}</li>
                        @if($promo->max_discount_value)
                          <li><strong>Giảm tối đa:</strong> {{ number_format($promo->max_discount_value, 0, ',', '.') }}₫</li>
                        @endif
                        <li><strong>Trạng thái:</strong> {{ ucfirst($promo->status) }}</li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-light border text-center">
              Hiện chưa có chương trình khuyến mãi nào đang diễn ra.
            </div>
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <!-- Sản phẩm đang được khuyến mãi -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2 w-max-content mx-auto">Sản phẩm khuyến mãi</h2>
        <p class="text-muted">Danh sách sản phẩm đang được áp dụng khuyến mãi</p>
      </div>
      <div class="row g-3">
        @forelse ($discountProducts as $product)
          <div class="col-6 col-md-4 col-lg-3">
            <x-product.card :product="$product" />
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-light border text-center mb-0">
              Chưa có sản phẩm nào được khuyến mãi.
            </div>
          </div>
        @endforelse
      </div>
    </div>
  </section>

</div>
@endsection
