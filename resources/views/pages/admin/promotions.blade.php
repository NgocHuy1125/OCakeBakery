@extends('layouts.admin.master')

@section('title', 'Quản lý khuyến mãi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0">Quản lý khuyến mãi</h4>
  <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalPromotion">
    <i class="bx bx-plus"></i> Thêm khuyến mãi
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="table" class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Tên chương trình</th>
            <th>Slug</th>
            <th>Loại giảm</th>
            <th>Giá trị</th>
            <th>Hiệu lực</th>
            <th>Trạng thái</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
          </thead>
        <tbody>
          @forelse($promotions as $promotion)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $promotion->name }}</td>
            <td>{{ $promotion->slug }}</td>
            <td>{{ $promotion->discount_type === 'percentage' ? 'Giảm %' : 'Giảm tiền' }}</td>
            <td>
              {{ $promotion->discount_type === 'percentage' ? $promotion->discount_value.'%' : number_format($promotion->discount_value, 0, ',', '.') . '₫' }}
            </td>
            <td>{{ optional($promotion->start_date)->format('d/m/Y H:i') }} - {{ optional($promotion->end_date)->format('d/m/Y H:i') }}</td>
            <td>
              <span class="badge 
                @if($promotion->status === 'active') bg-success 
                @elseif($promotion->status === 'expired') bg-secondary 
                @else bg-warning text-dark 
                @endif">
                {{ ucfirst($promotion->status) }}
              </span>
            </td>
            <td class="text-end pe-4">
              <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editPromotion{{ $promotion->id }}">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" class="d-inline confirm-delete">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @push('modals')
          <div class="modal fade" id="editPromotion{{ $promotion->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Chỉnh sửa khuyến mãi</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="modal-body">
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Tên chương trình</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $promotion->name) }}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $promotion->slug) }}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Loại giảm</label>
                        <select name="discount_type" class="form-select" required>
                          <option value="percentage" @selected(old('discount_type', $promotion->discount_type) === 'percentage')>Giảm theo %</option>
                          <option value="amount" @selected(old('discount_type', $promotion->discount_type) === 'amount')>Giảm cố định</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Giá trị</label>
                        <input type="number" step="0.01" min="0" name="discount_value" class="form-control" value="{{ old('discount_value', $promotion->discount_value) }}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Giá trị giảm tối đa</label>
                        <input type="number" step="0.01" min="0" name="max_discount_value" class="form-control" value="{{ old('max_discount_value', $promotion->max_discount_value) }}">
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" class="form-control" value="{{ old('start_date', optional($promotion->start_date)->format('Y-m-d\\TH:i')) }}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="datetime-local" name="end_date" class="form-control" value="{{ old('end_date', optional($promotion->end_date)->format('Y-m-d\\TH:i')) }}" required>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Ảnh banner</label>
                        <input type="file" name="banner" class="form-control" accept="image/*">
                        <small class="text-muted">Chọn ảnh mới nếu muốn thay thế banner hiện tại.</small>
                        @if($promotion->banner_url)
                          <div class="mt-2">
                            <img src="{{ asset($promotion->banner_url) }}" alt="{{ $promotion->name }}" class="img-fluid rounded border" style="max-height:120px;object-fit:cover;">
                          </div>
                        @endif
                      </div>
                      <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Mô tả ngắn">{{ old('description', $promotion->description) }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          @endpush
          @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">Chưa có khuyến mãi nào.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Thêm khuyến mãi -->
<div class="modal fade" id="modalPromotion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm khuyến mãi mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Tên chương trình</label>
              <input type="text" name="name" class="form-control" placeholder="Nhập tên khuyến mãi..." required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Slug</label>
              <input type="text" name="slug" class="form-control" placeholder="vi-du-khuyen-mai" required>
              <small class="text-muted">Slug không dấu, phân tách bằng dấu gạch ngang.</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Loại</label>
              <select name="discount_type" class="form-select" required>
                <option value="percentage">Giảm theo %</option>
                <option value="amount">Giảm cố định</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Giá trị</label>
              <input type="number" step="0.01" min="0" name="discount_value" class="form-control" placeholder="VD: 10 hoặc 50000" required>
            </div>

            <div class="col-md-6">
              <label class="form-label">Giá trị giảm tối đa (tuỳ chọn)</label>
              <input type="number" step="0.01" min="0" name="max_discount_value" class="form-control" placeholder="Bỏ trống nếu không giới hạn">
            </div>

            <div class="col-md-6">
              <label class="form-label">Ngày bắt đầu</label>
              <input type="datetime-local" name="start_date" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ngày kết thúc</label>
              <input type="datetime-local" name="end_date" class="form-control" required>
            </div>

            <div class="col-md-12">
              <label class="form-label">Mô tả (tuỳ chọn)</label>
              <textarea name="description" rows="3" class="form-control" placeholder="Thêm mô tả ngắn về chương trình"></textarea>
            <div class="col-md-12">
              <label class="form-label">Ảnh banner (tuỳ chọn)</label>
              <input type="file" name="banner" class="form-control" accept="image/*">
              <small class="text-muted">Định dạng JPG/PNG, tối đa 2MB.</small>
            </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-success">Lưu</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
