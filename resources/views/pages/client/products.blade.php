@extends('layouts.client.master')

@section('title', 'Sản phẩm')

@section('content')
<div class="products-page">
  <!-- Hero -->
  <section class="hero-banner mb-5 position-relative text-center text-white rounded-4 overflow-hidden hero-bg-bakery">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="container-fluid hero-content position-relative p-5">
      <h1 class="fw-bold display-5 mb-3 text-uppercase">Bánh ngon mỗi ngày</h1>
      <p class="lead mb-4 col-md-8 col-12 mx-auto">
        Hơn 50 sản phẩm với các loại danh mục khác nhau, toàn bộ đều được đảm bảo chất lượng tốt nhất khi đưa đến tay quý khách hàng
      </p>
      <a href="#products" class="btn btn-light btn-lg px-3">
        Khám phá ngay <i class="fas fa-arrow-up-right-from-square ms-1"></i>
      </a>
    </div>
  </section>

  <!-- Filters + Search -->
  <section class="mb-4" id="products">
    <form class="d-flex flex-column gap-3" method="GET" action="{{ route('products.index') }}">
      <div class="d-flex flex-wrap align-items-center gap-2">
        @php
          $activeCategory = request('category');
          $queryExceptPage = request()->except(['page']);
          $allParams = $queryExceptPage;
          unset($allParams['category']);
          $allUrl = route('products.index', $allParams);
        @endphp
        <a href="{{ $allUrl }}"
           class="btn btn-sm rounded-pill {{ !$activeCategory ? 'btn-success' : 'btn-outline-success cat-pill' }}">
          Tất cả
        </a>
        @foreach($categories as $category)
          @php
            $categoryParams = array_merge($queryExceptPage, ['category' => $category->slug]);
            unset($categoryParams['page']);
            $categoryUrl = route('products.index', $categoryParams);
          @endphp
          <a href="{{ $categoryUrl }}"
             class="btn btn-sm rounded-pill {{ $activeCategory === $category->slug ? 'btn-success' : 'btn-outline-success cat-pill' }}">
            {{ $category->name }}
          </a>
        @endforeach
      </div>

      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="input-group" style="max-width: 620px;">
          <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
          <input type="search"
                 class="form-control"
                 name="q"
                 value="{{ request('q') }}"
                 placeholder="Tìm theo tên bánh, mã sản phẩm...">
        </div>
      </div>
    </form>
  </section>

  <!-- Product grid -->
  <section class="mb-4">
    @if($products->count())
      <div class="row g-3 g-md-4">
        @foreach($products as $product)
          <div class="col-6 col-md-4 col-lg-3">
            @include('components.product.card', ['product' => $product])
          </div>
        @endforeach
      </div>

      <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <div class="text-muted small">
          Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm
        </div>
        <div class="ms-auto">
          {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
      </div>

    @else
      <div class="alert alert-warning">
        Không tìm thấy sản phẩm phù hợp với tiêu chí tìm kiếm.
      </div>
    @endif
  </section>
</div>
@endsection
