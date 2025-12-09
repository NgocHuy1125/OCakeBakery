@extends('layouts.admin.master')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Danh sách sản phẩm</h4>
  <a href="{{ route('admin.products.create') }}" class="btn btn-outline-primary">
    <i class="bx bx-plus"></i> Thêm sản phẩm
  </a>
</div>

@php
  $statusFilters = [
    'draft' => 'Nháp',
    'active' => 'Đang bán',
    'out_of_stock' => 'Hết hàng',
    'archived' => 'Ngừng bán',
  ];
  $perPageOptions = [15, 30, 50, 100];
@endphp

<form method="GET" class="row g-3 align-items-end mb-3">
  <div class="col-md-4">
    <label class="form-label fw-semibold">Từ khóa</label>
    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên hoặc mã sản phẩm">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-semibold">Trạng thái</label>
    <select name="status" class="form-select">
      <option value="">Tất cả</option>
      @foreach($statusFilters as $value => $label)
        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2 col-sm-6">
    <label class="form-label fw-semibold">Mỗi trang</label>
    <select name="per_page" class="form-select">
      @foreach($perPageOptions as $option)
        <option value="{{ $option }}" @selected((int) request('per_page', $perPage ?? 50) === $option)>{{ $option }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3 col-sm-6 d-flex gap-2">
    <button type="submit" class="btn btn-primary flex-grow-1">Lọc</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Mã sản phẩm</th>
            <th>Sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Hiển thị</th>
            <th>Trạng thái</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $product->product_code }}</td>
            <td>
              <div class="d-flex align-items-center">
                @php
                  $imageUrl = $product->primary_image_url ?: asset('images/product.jpg');
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" width="60" height="60"
                     class="rounded border object-fit-cover img-fluid me-2" loading="lazy"
                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                <span>{{ $product->name }}</span>
              </div>
            </td>
            <td>{{ $product->primaryCategory->name ?? '—' }}</td>
            <td>
              @if($product->sale_price)
                <div>
                  <span class="fw-semibold text-success">{{ number_format($product->sale_price, 0, ',', '.') }} ₫</span>
                  <small class="text-muted text-decoration-line-through d-block">
                    {{ number_format($product->listed_price, 0, ',', '.') }} ₫
                  </small>
                </div>
              @else
                <span class="fw-semibold">{{ number_format($product->listed_price, 0, ',', '.') }} ₫</span>
              @endif
            </td>
            <td class="text-center">{{ $product->total_stock }}</td>
            <td class="text-center">
              @if($product->show_on_homepage)
                <i class="fas fa-home text-info"></i>
              @endif
              @if($product->is_featured)
                <i class="fas fa-crown text-warning ms-1"></i>
              @endif
              @if(!$product->show_on_homepage && !$product->is_featured)
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              @php
                $statusMap = [
                  'draft' => ['Nháp', 'bg-secondary-subtle text-secondary'],
                  'active' => ['Đang bán', 'bg-success-subtle text-success'],
                  'out_of_stock' => ['Hết hàng', 'bg-warning-subtle text-warning'],
                  'archived' => ['Ngừng bán', 'bg-dark-subtle text-dark'],
                ];
                [$label, $class] = $statusMap[$product->status] ?? [$product->status, 'bg-light text-dark'];
              @endphp
              <span class="badge {{ $class }}">{{ $label }}</span>
            </td>
            <td class="text-end">
              <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-edit"></i>
              </a>
              <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline confirm-delete">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center text-muted py-4">
              <i class="bx bx-cube-alt fs-3 mb-1 d-block"></i> Chưa có sản phẩm nào.
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($products->total())
    <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
      <small class="text-muted">
        Hiển thị {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}
        trong tổng số {{ $products->total() }} sản phẩm
      </small>
      {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>
@endsection
