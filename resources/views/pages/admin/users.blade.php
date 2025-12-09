@extends('layouts.admin.master')

@section('title', 'Quản lý tài khoản')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Danh sách tài khoản</h4>
  <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUser">
    <i class="bx bx-plus"></i> Thêm tài khoản
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="table" class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $user->full_name }}</td>
              <td>{{ $user->email ?? '—' }}</td>
              <td>{{ $user->phone_number ?? '—' }}</td>
              <td><span class="badge bg-info-subtle text-info text-capitalize">{{ $user->role }}</span></td>
              <td>
                @if($user->status === 'active')
                  <span class="badge bg-success-subtle text-success">Hoạt động</span>
                @elseif($user->status === 'inactive')
                  <span class="badge bg-secondary-subtle text-secondary">Không hoạt động</span>
                @elseif($user->status === 'suspended')
                  <span class="badge bg-warning-subtle text-warning">Tạm khóa</span>
                @else
                  <span class="badge bg-danger-subtle text-danger">Đã xóa</span>
                @endif
              </td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalUserEdit-{{ $user->id }}">
                  <i class="fas fa-edit"></i>
                </button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline confirm-delete">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>

            {{-- Modal sửa tài khoản --}}
            <div class="modal fade" id="modalUserEdit-{{ $user->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Cập nhật tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label">Họ tên</label>
                          <input type="text" name="full_name" value="{{ $user->full_name }}" class="form-control" placeholder="Nhập họ tên...">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="Nhập email...">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Số điện thoại</label>
                          <input type="text" name="phone_number" value="{{ $user->phone_number }}" class="form-control" placeholder="Nhập số điện thoại...">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Giới tính</label>
                          <select name="gender" class="form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="male" @selected($user->gender === 'male')>Nam</option>
                            <option value="female" @selected($user->gender === 'female')>Nữ</option>
                            <option value="other" @selected($user->gender === 'other')>Khác</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Ngày sinh</label>
                          <input type="date" name="date_of_birth" value="{{ $user->date_of_birth }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Vai trò</label>
                          <select name="role" class="form-select">
                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                            <option value="staff" @selected($user->role === 'staff')>Nhân viên</option>
                            <option value="customer" @selected($user->role === 'customer')>Khách hàng</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Trạng thái</label>
                          <select name="status" class="form-select">
                            <option value="active" @selected($user->status === 'active')>Hoạt động</option>
                            <option value="inactive" @selected($user->status === 'inactive')>Không hoạt động</option>
                            <option value="suspended" @selected($user->status === 'suspended')>Tạm khóa</option>
                            <option value="deleted" @selected($user->status === 'deleted')>Đã xóa</option>
                          </select>
                        </div>
                        <div class="col-12">
                          <label class="form-label">Mật khẩu mới (nếu muốn đổi)</label>
                          <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới...">
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
          @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                <i class="bx bx-user-circle fs-3 mb-1 d-block"></i>
                Chưa có tài khoản nào.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Modal thêm tài khoản --}}
<div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm tài khoản mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Họ tên</label>
              <input type="text" name="full_name" class="form-control" placeholder="Nhập họ tên...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Nhập email...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Số điện thoại</label>
              <input type="text" name="phone_number" class="form-control" placeholder="Nhập số điện thoại...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Giới tính</label>
              <select name="gender" class="form-select">
                <option value="">-- Chọn giới tính --</option>
                <option value="male">Nam</option>
                <option value="female">Nữ</option>
                <option value="other">Khác</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ngày sinh</label>
              <input type="date" name="date_of_birth" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Vai trò</label>
              <select name="role" class="form-select">
                <option value="">-- Chọn vai trò --</option>
                <option value="admin">Admin</option>
                <option value="staff">Nhân viên</option>
                <option value="customer">Khách hàng</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Mật khẩu</label>
              <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu...">
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
