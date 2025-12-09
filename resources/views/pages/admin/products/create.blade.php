@extends('layouts.admin.master')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0">Thêm sản phẩm</h4>
  <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark">
    <i class="fas fa-angle-left me-1"></i> Quay lại
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="row g-4">
      @csrf

      <div class="col-lg-8">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm..." value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Mã sản phẩm</label>
            <input type="text" name="product_code" id="product_code" class="form-control" readonly required>
            @error('product_code') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" placeholder="Tự sinh nếu để trống" value="{{ old('slug') }}">
            @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-12">
            <label class="form-label">Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="2" placeholder="Tóm tắt ngắn gọn về sản phẩm...">{{ old('short_description') }}</textarea>
            @error('short_description') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-12">
            <label class="form-label">Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="5" placeholder="Mô tả chi tiết, thành phần, hướng dẫn...">{{ old('description') }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="col-12">
            <label class="form-label">Ảnh sản phẩm</label>
            <input type="file" name="image_files[]" id="imageUpload" class="form-control" accept="image/*" multiple required>
            @error('image_files.*') <small class="text-danger d-block">{{ $message }}</small> @enderror
            <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-2"></div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="vstack gap-3">
          <div>
            <label class="form-label">Danh mục chính</label>
            <select name="primary_category_id" class="form-select" required>
              <option value="">-- Chọn danh mục --</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('primary_category_id') == $category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
            @error('primary_category_id') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="row g-3">
            <div class="col-6">
              <label class="form-label">Giá niêm yết</label>
              <input type="number" step="0.01" name="listed_price" class="form-control" placeholder="VD: 25000" value="{{ old('listed_price') }}" required>
              @error('listed_price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-6">
              <label class="form-label">Giá khuyến mãi</label>
              <input type="number" step="0.01" name="sale_price" class="form-control" placeholder="VD: 20000" value="{{ old('sale_price') }}">
              @error('sale_price') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-6">
              <label class="form-label">Tồn kho ban đầu</label>
              <input type="number" name="total_stock" class="form-control" placeholder="VD: 50" value="{{ old('total_stock', 0) }}">
              @error('total_stock') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-6">
              <label class="form-label">Đơn vị tính</label>
              <input type="text" name="unit_name" class="form-control" placeholder="VD: ly, hộp..." value="{{ old('unit_name', 'sản phẩm') }}">
              @error('unit_name') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="col-6">
              <label class="form-label">Trạng thái</label>
              <select name="status" class="form-select" required>
                <option value="draft" @selected(old('status') === 'draft')>Nháp</option>
                <option value="active" @selected(old('status') === 'active')>Đang bán</option>
                <option value="out_of_stock" @selected(old('status') === 'out_of_stock')>Hết hàng</option>
                <option value="archived" @selected(old('status') === 'archived')>Ngừng bán</option>
              </select>
              @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>

          <div class="row g-3">
            <div class="col-6">
              <label class="form-label">Tên biến thể</label>
              <input type="text" name="variant_name" class="form-control" placeholder="VD: Size M" value="{{ old('variant_name', 'Mặc định') }}">
            </div>
            <div class="col-6">
              <label class="form-label">SKU</label>
              <input type="text" name="sku" class="form-control" placeholder="VD: SKU001" value="{{ old('sku') }}">
            </div>
            <div class="col-6">
              <label class="form-label">Giá biến thể</label>
              <input type="number" step="0.01" name="variant_price" class="form-control" placeholder="Giá theo biến thể (nếu có)" value="{{ old('variant_price') }}">
            </div>
            <div class="col-6">
              <label class="form-label">Tồn kho biến thể</label>
              <input type="number" name="variant_stock_quantity" class="form-control" placeholder="VD: 30" value="{{ old('variant_stock_quantity') }}">
            </div>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="show_on_homepage" id="show_on_homepage" value="1" @checked(old('show_on_homepage'))>
            <label for="show_on_homepage" class="form-check-label">Hiển thị trên trang chủ</label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured'))>
            <label for="is_featured" class="form-check-label">Sản phẩm nổi bật</label>
          </div>

          <button type="submit" class="btn btn-success mt-3">Lưu sản phẩm</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('product_code');
  input.value = generateProductCode();

  document.getElementById('imageUpload').addEventListener('change', function (e) {
    const container = document.getElementById('previewContainer');
    container.innerHTML = '';
    for (const file of e.target.files) {
      if (!file.type.startsWith('image/')) continue;
      const reader = new FileReader();
      reader.onload = ev => {
        const img = document.createElement('img');
        img.src = ev.target.result;
        img.width = 90;
        img.height = 90;
        img.classList.add('rounded', 'border', 'object-fit-cover');
        container.appendChild(img);
      };
      reader.readAsDataURL(file);
    }
  });
});

function generateProductCode() {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  let result = 'SP';
  for (let i = 0; i < 9; i += 1) {
    result += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  return result;
}
</script>
@endsection
