@extends('layouts.client.master')

@section('title', 'Tìm kiếm sản phẩm')

@section('content')
@php
    $keyword = request('q', request('keyword', ''));
@endphp

<div class="search-page">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h1 class="h4 mb-0">Kết quả tìm kiếm cho: “{{ $keyword }}”</h1>
    <form action="{{ route('products.search') }}" method="GET" class="d-flex" style="max-width:320px">
      <input type="search" name="q" class="form-control me-2" placeholder="Nhập từ khoá..." value="{{ $keyword }}">
      <button class="btn btn-success" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>

  @if($products->count())
    <div class="row g-3">
      @foreach($products as $product)
        <div class="col-6 col-lg-3">
          @include('components.product.card', ['product' => $product, 'class' => 'h-100'])
        </div>
      @endforeach
    </div>
    <div class="d-flex justify-content-end mt-3">
      {{ $products->onEachSide(1)->links() }}
    </div>
  @else
    <div class="alert alert-warning">Không tìm thấy sản phẩm phù hợp với từ khoá “{{ $keyword }}”.</div>
  @endif
</div>
@endsection

@push('styles')
<style>
.search-page .product-card{border:1px solid #e9ecef;border-radius:.75rem;overflow:hidden;background:#fff;transition:transform .15s}
.search-page .product-card:hover{transform:translateY(-2px)}
.search-page .product-img-wrap{aspect-ratio:4/3;overflow:hidden}
.search-page .product-img{width:100%;height:100%;object-fit:cover}
.search-page .old-price{color:#94a3b8;text-decoration:line-through}
</style>
@endpush
