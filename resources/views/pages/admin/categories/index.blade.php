@extends('layouts.admin.master')

@section('title', 'Danh mục sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Danh mục sản phẩm</h4>
  <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#createCategoryForm" aria-expanded="false" aria-controls="createCategoryForm">
    <i class="bx bx-plus"></i> Thêm danh mục mới
  </button>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="collapse" id="createCategoryForm">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0 fw-semibold">Tạo danh mục mới</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="vstack gap-3">
        @csrf
        <div>
          <label class="form-label fw-semibold">Tên danh mục</label>
          <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục" value="{{ old('name') }}" required>
          @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div>
          <label class="form-label fw-semibold">Slug</label>
          <input type="text" name="slug" class="form-control" placeholder="Tự sinh nếu để trống, ví dụ: banh-kem-truyen-thong" value="{{ old('slug') }}">
          @error('slug') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div>
          <label class="form-label fw-semibold">Ảnh danh mục</label>
          <input type="file" name="image" class="form-control" accept="image/*">
          <small class="text-muted">Định dạng: JPG, PNG, WEBP. Tối đa 2MB.</small>
          @error('image') <small class="text-danger d-block">{{ $message }}</small> @enderror
        </div>

        <div>
          <label class="form-label fw-semibold">Mô tả ngắn</label>
          <textarea name="short_description" class="form-control" rows="3" placeholder="Mô tả ngắn gọn về danh mục...">{{ old('short_description') }}</textarea>
          @error('short_description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Thứ tự hiển thị</label>
            <input type="number" name="display_order" class="form-control" placeholder="0 = mặc định" value="{{ old('display_order', 0) }}">
            @error('display_order') <small class="text-danger">{{ $message }}</small> @enderror
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1" {{ old('is_visible', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_visible">Hiển thị</label>
            </div>
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save me-1"></i> Lưu danh mục
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="table" class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Ảnh</th>
            <th>Tên danh mục</th>
            <th>Slug</th>
            <th>Thứ tự</th>
            <th>Hiển thị</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              @if($category->image_full_url)
                <img src="{{ $category->image_full_url }}" alt="{{ $category->name }}" width="60" height="60" class="rounded border object-fit-cover">
              @else
                <img src="{{ asset('images/no-image.png') }}" alt="No Image" width="60" height="60" class="rounded border object-fit-cover opacity-50">
              @endif
            </td>

            <td class="fw-semibold">{{ $category->name }}</td>
            <td><span class="truncate-250">{{ $category->slug }}</span></td>
            <td class="text-center">{{ $category->display_order }}</td>

            <td class="text-center">
              <span class="badge {{ $category->is_visible ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                {{ $category->is_visible ? 'Hiển thị' : 'Ẩn' }}
              </span>
            </td>

            <td class="text-end pe-4">
              <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCategory{{ $category->id }}">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline confirm-delete">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>

          <!-- Modal chỉnh sửa -->
          @push('modals')
          <div class="modal fade" id="editCategory{{ $category->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title fw-semibold">Chỉnh sửa danh mục</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Tên danh mục</label>
                      <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Slug</label>
                      <input type="text" name="slug" class="form-control" value="{{ $category->slug }}">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Ảnh danh mục</label><br>
                      @if($category->image_full_url)
                        <img src="{{ $category->image_full_url }}" width="80" class="rounded border mb-2">
                      @endif
                      <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Mô tả ngắn</label>
                      <textarea name="short_description" class="form-control" rows="3">{{ $category->short_description }}</textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Thứ tự hiển thị</label>
                      <input type="number" name="display_order" class="form-control" value="{{ $category->display_order }}">
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="is_visible" id="edit-visible-{{ $category->id }}" value="1" {{ $category->is_visible ? 'checked' : '' }}>
                      <label for="edit-visible-{{ $category->id }}" class="form-check-label">Hiển thị</label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Lưu</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          @endpush

          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="bx bx-category fs-3 mb-1 d-block"></i> Chưa có danh mục nào.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
