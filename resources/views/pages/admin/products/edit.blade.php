@extends('layouts.admin.master')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Chỉnh sửa sản phẩm</h4>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">
    <i class="fas fa-angle-left me-1"></i> Quay lại
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="{{ route('admin.products.update', $product) }}"
          method="POST" enctype="multipart/form-data" class="row g-4">
      @csrf
      @method('PUT')

      <div class="col-lg-8">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $product->name) }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Mã sản phẩm</label>
            <input type="text" name="product_code" class="form-control"
                   value="{{ old('product_code', $product->product_code) }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" class="form-control"
                   value="{{ old('slug', $product->slug) }}" placeholder="Tự sinh nếu để trống">
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Ảnh sản phẩm</label>
            <input type="file" name="image_files[]" multiple accept="image/*" class="form-control" id="imageUpload">
            <div id="previewImages" class="d-flex flex-wrap gap-2 mt-3">
              @forelse($product->images as $image)
                <img src="{{ $image->resolved_url ?? asset('images/product.jpg') }}" width="100" height="100"
                     class="rounded border object-fit-cover current-image">
              @empty
                <img src="{{ $product->primary_image_url ?? asset('images/product.jpg') }}" width="100" height="100"
                     class="rounded border object-fit-cover current-image">
              @endforelse
            </div>
            <small class="text-muted">Chọn ảnh mới nếu bạn muốn thay thế bộ ảnh hiện tại.</small>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="vstack gap-3">
          <div>
            <label class="form-label fw-semibold">Danh mục chính</label>
            <select name="primary_category_id" class="form-select" required>
              <option value="">Chọn danh mục</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}"
                        @selected(old('primary_category_id', $product->primary_category_id) == $category->id)>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="row g-3">
            <div class="col-6">
              <label class="form-label fw-semibold">Giá niêm yết</label>
              <input type="number" step="0.01" name="listed_price" class="form-control"
                     value="{{ old('listed_price', $product->listed_price) }}" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Giá khuyến mãi</label>
              <input type="number" step="0.01" name="sale_price" class="form-control"
                     value="{{ old('sale_price', $product->sale_price) }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Tồn kho</label>
              <input type="number" name="total_stock" class="form-control"
                     value="{{ old('total_stock', $product->total_stock) }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Đơn vị tính</label>
              <input type="text" name="unit_name" class="form-control"
                     value="{{ old('unit_name', $product->unit_name ?? 'sản phẩm') }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Trạng thái</label>
              <select name="status" class="form-select">
                <option value="draft" @selected(old('status', $product->status) == 'draft')>Nháp</option>
                <option value="active" @selected(old('status', $product->status) == 'active')>Đang bán</option>
                <option value="out_of_stock" @selected(old('status', $product->status) == 'out_of_stock')>Hết hàng</option>
                <option value="archived" @selected(old('status', $product->status) == 'archived')>Ngừng bán</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Tên biến thể</label>
              <input type="text" name="variant_name" class="form-control"
                     value="{{ old('variant_name', $variant->variant_name ?? 'Mặc định') }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">SKU</label>
              <input type="text" name="sku" class="form-control"
                     value="{{ old('sku', $variant->sku ?? '') }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Giá biến thể</label>
              <input type="number" step="0.01" name="variant_price" class="form-control"
                     value="{{ old('variant_price', $variant->price ?? $product->listed_price) }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">KM biến thể</label>
              <input type="number" step="0.01" name="variant_sale_price" class="form-control"
                     value="{{ old('variant_sale_price', $variant->sale_price ?? $product->sale_price) }}">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Tồn kho biến thể</label>
              <input type="number" name="variant_stock_quantity" class="form-control"
                     value="{{ old('variant_stock_quantity', $variant->stock_quantity ?? $product->total_stock ?? 0) }}">
            </div>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="show_on_homepage"
                   id="show_on_homepage" value="1"
                   @checked(old('show_on_homepage', $product->show_on_homepage))>
            <label for="show_on_homepage" class="form-check-label">Hiển thị trang chủ</label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured"
                   id="is_featured" value="1"
                   @checked(old('is_featured', $product->is_featured))>
            <label for="is_featured" class="form-check-label">Sản phẩm nổi bật</label>
          </div>

          <button type="submit" class="btn btn-success w-100">Cập nhật sản phẩm</button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('imageUpload')?.addEventListener('change', function (e) {
  const preview = document.getElementById('previewImages');
  preview.innerHTML = '';

  Array.from(e.target.files).forEach((file) => {
    if (!file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = (event) => {
      const img = document.createElement('img');
      img.src = event.target.result;
      img.className = 'rounded border object-fit-cover';
      img.style = 'width:100px;height:100px;margin-right:6px';
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
});
</script>
@endpush

@endsection
