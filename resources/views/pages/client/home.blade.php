@extends('layouts.client.master')

@section('title', 'Trang chủ')

@section('content')
<section class="hero-banner mb-5 position-relative text-center text-white rounded-4 overflow-hidden">
  <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
  <div class="container-fluid hero-content position-relative p-5">
    <h1 class="fw-bold display-5 mb-3 text-uppercase">
      Chào mừng đến với <span class="text-warning">Tiệm Bánh Kim Loan</span>
    </h1>
    <p class="lead mb-4">Ngọt ngào mỗi ngày cùng những chiếc bánh tươi mới & sáng tạo.</p>
    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg px-5">
      <i class="fas fa-shopping-cart me-1"></i> Xem sản phẩm
    </a>
  </div>
</section>

<section class="p-4 bg-success text-white rounded mb-5">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="text-white fw-bold mb-0 text-uppercase text-underline">Danh mục sản phẩm</h2>
    </div>
    <div class="swiper category-swiper">
      <div class="swiper-wrapper">
        @forelse($categories as $category)
          <div class="swiper-slide">
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
              <div class="card shadow-sm border-0 h-100">
                <img src="{{ $category->image_full_url ?? asset('/images/default-category.jpg') }}" class="card-img-top" alt="{{ $category->name }}">
                <div class="card-body text-center">
                  <h6 class="card-title text-dark">{{ $category->name }}</h6>
                </div>
              </div>
            </a>
          </div>
        @empty
          <div class="swiper-slide">
            <div class="alert alert-light border text-center w-100 mb-0">Chưa có danh mục sản phẩm nào.</div>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="p-3 bg-white position-relative">
    <h2 class="section-title position-absolute text-success fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2">Best Seller</h2>
    <div class="row g-3 pt-4">
      <p class="text-center lead">Danh sách sản phẩm được ưa thích nhất tại Tiệm bánh Kim Loan</p>
      @forelse($homeProducts as $product)
        <div class="col-6 col-md-4 col-lg-3">
          <x-product.card :product="$product" class="shadow-sm border-0" />
        </div>
      @empty
        <div class="col-12"><div class="alert alert-light border text-center mb-0">Chưa có sản phẩm nổi bật.</div></div>
      @endforelse
    </div>
  </div>
</section>

<section class="mb-5 products-section">
  <div class="position-relative rounded shadow-sm">
    <div class="row">
      <div class="col-md-3 mb-3 mb-md-0 category-panel bg-success rounded">
        <h4 class="text-uppercase text-white fw-bold text-center pt-3">Danh mục sản phẩm</h4>
        <hr class="text-white">
        <ul class="nav nav-tabs flex-column border-0" id="productTabs" role="tablist">
          <li class="nav-item">
            <button class="nav-link active fw-semibold w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-banhkem">Bánh Kem</button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-semibold w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-banhngot">Bánh Ngọt</button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-semibold w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-mousse">Bánh Mousse Trái Cây</button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-semibold w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-tiramisu">Bánh Tiramisu</button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-semibold w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-teabreak">Tea Break</button>
          </li>
          <li class="nav-item">
            <button class="nav-link fw-semibold w-100 text-start" data-bs-toggle="tab" data-bs-target="#tab-sinhnhat">Bánh Sinh Nhật</button>
          </li>
        </ul>
      </div>

      <div class="col-md-9 bg-white p-3 rounded-5">
        <div class="tab-content">

          {{-- BÁNH KEM --}}
          <div class="tab-pane fade show active" id="tab-banhkem">
            <h3 class="fw-bold text-success text-uppercase mb-3">Bánh Kem</h3>
            <div class="row g-3">
              @forelse($banhkem as $product)
                <div class="col-6 col-md-4 col-lg-3">
                  <x-product.card :product="$product" class="shadow-sm border-0 h-100" />
                </div>
              @empty
                <div class="col-12"><div class="alert alert-light border text-center">Chưa có bánh kem nào.</div></div>
              @endforelse
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $banhkem->links('pagination::bootstrap-5') }}</div>
          </div>

          {{-- BÁNH NGỌT --}}
          <div class="tab-pane fade" id="tab-banhngot">
            <h3 class="fw-bold text-success text-uppercase mb-3">Bánh Ngọt</h3>
            <div class="row g-3">
              @forelse($banhngot as $product)
                <div class="col-6 col-md-4 col-lg-3">
                  <x-product.card :product="$product" class="shadow-sm border-0 h-100" />
                </div>
              @empty
                <div class="col-12"><div class="alert alert-light border text-center">Chưa có bánh ngọt nào.</div></div>
              @endforelse
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $banhngot->links('pagination::bootstrap-5') }}</div>
          </div>

          {{-- MOUSSE --}}
          <div class="tab-pane fade" id="tab-mousse">
            <h3 class="fw-bold text-success text-uppercase mb-3">Bánh Mousse Trái Cây</h3>
            <div class="row g-3">
              @forelse($mousse as $product)
                <div class="col-6 col-md-4 col-lg-3">
                  <x-product.card :product="$product" class="shadow-sm border-0 h-100" />
                </div>
              @empty
                <div class="col-12"><div class="alert alert-light border text-center">Chưa có bánh mousse nào.</div></div>
              @endforelse
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $mousse->links('pagination::bootstrap-5') }}</div>
          </div>

          {{-- TIRAMISU --}}
          <div class="tab-pane fade" id="tab-tiramisu">
            <h3 class="fw-bold text-success text-uppercase mb-3">Bánh Tiramisu</h3>
            <div class="row g-3">
              @forelse($tiramisu as $product)
                <div class="col-6 col-md-4 col-lg-3">
                  <x-product.card :product="$product" class="shadow-sm border-0 h-100" />
                </div>
              @empty
                <div class="col-12"><div class="alert alert-light border text-center">Chưa có bánh tiramisu nào.</div></div>
              @endforelse
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $tiramisu->links('pagination::bootstrap-5') }}</div>
          </div>

          {{-- TEA BREAK --}}
          <div class="tab-pane fade" id="tab-teabreak">
            <h3 class="fw-bold text-success text-uppercase mb-3">Tea Break</h3>
            <div class="row g-3">
              @forelse($teabreak as $product)
                <div class="col-6 col-md-4 col-lg-3">
                  <x-product.card :product="$product" class="shadow-sm border-0 h-100" />
                </div>
              @empty
                <div class="col-12"><div class="alert alert-light border text-center">Chưa có sản phẩm Tea Break nào.</div></div>
              @endforelse
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $teabreak->links('pagination::bootstrap-5') }}</div>
          </div>

          {{-- SINH NHẬT --}}
          <div class="tab-pane fade" id="tab-sinhnhat">
            <h3 class="fw-bold text-success text-uppercase mb-3">Bánh Sinh Nhật</h3>
            <div class="row g-3">
              @forelse($sinhnhat as $product)
                <div class="col-6 col-md-4 col-lg-3">
                  <x-product.card :product="$product" class="shadow-sm border-0 h-100" />
                </div>
              @empty
                <div class="col-12"><div class="alert alert-light border text-center">Chưa có bánh sinh nhật nào.</div></div>
              @endforelse
            </div>
            <div class="mt-3 d-flex justify-content-center">{{ $sinhnhat->links('pagination::bootstrap-5') }}</div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2 w-max-content mx-auto">Ưu đãi đang diễn ra</h2>
      <p class="text-muted">Nhanh tay chọn bánh, nhận ưu đãi hấp dẫn trong tháng này!</p>
    </div>
    <div class="row g-3">
      @forelse($activePromotions as $promo)
        <div class="col-md-4 col-12">
          <div class="card border-0 shadow-sm h-100">
            <img src="{{ asset('/images/promotions.jpg') }}" class="card-img-top" alt="{{ $promo->name }}">
            <div class="card-body">
              <h5 class="fw-bold text-success text-uppercase mb-2">{{ $promo->name }}</h5>
              <p class="text-muted small mb-3">{{ $promo->description ?? 'Ưu đãi hấp dẫn trong tháng này!' }}</p>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                  <i class="fas fa-calendar-alt me-1"></i>{{ optional($promo->start_date)->format('d/m/Y') }} - {{ optional($promo->end_date)->format('d/m/Y') }}
                </small>
                <a href="{{ route('client.promotions') }}" class="btn btn-outline-success btn-sm">Xem chi tiết</a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12"><div class="alert alert-light border text-center">Chưa có khuyến mãi nào.</div></div>
      @endforelse
    </div>
  </div>
</section>
@endsection
