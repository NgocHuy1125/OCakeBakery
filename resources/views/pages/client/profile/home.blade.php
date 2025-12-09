@extends('layouts.client.master')

@section('title', 'Hồ sơ cá nhân')

@section('content')
@php
    $statusLabels = [
        'active' => 'Đang hoạt động',
        'inactive' => 'Đã vô hiệu hóa',
        'suspended' => 'Tạm khóa',
        'deleted' => 'Đã xóa',
    ];
    $accountStatus = $statusLabels[auth()->user()->status] ?? ucfirst(auth()->user()->status);
@endphp

<div class="container py-5">
  <div class="row g-4">
    {{-- Cột trái --}}
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center">
          <div class="mb-3">
            <img src="{{ asset('/images/logo.png') }}" width="80" height="80" class="img-fluid rounded-circle">
          </div>
          <h5 class="fw-semibold mb-1">{{ auth()->user()->full_name }}</h5>
          <p class="text-muted mb-2">{{ auth()->user()->email ?? auth()->user()->phone_number }}</p>
          <span class="badge bg-success-subtle text-success">
            Mã khách hàng: {{ auth()->user()->customer_code ?? '—' }}
          </span>
        </div>
        <div class="card-footer bg-white">
          <div class="row text-center g-3">
            <div class="col-6">
              <div class="card border-0 shadow-sm py-3">
                <a href="{{ route('cart.index') }}" class="text-decoration-none text-dark d-block">
                  <div class="position-relative d-inline-block mb-2">
                    <i class="fas fa-shopping-cart fa-2x"></i>
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill">
                      {{ $cartCount ?? 0 }}
                    </span>
                  </div>
                  <div class="fw-semibold mt-1">Giỏ hàng</div>
                </a>
              </div>
            </div>
            <div class="col-6">
              <div class="card border-0 shadow-sm py-3">
                <a href="{{ route('profile.orders', ['status' => 'placed']) }}" class="text-decoration-none text-dark d-block">
                  <div class="position-relative d-inline-block mb-2">
                    <i class="fas fa-list-check fa-2x"></i>
                    <span class="badge bg-primary position-absolute top-0 start-100 translate-middle rounded-pill">
                      {{ $placedCount ?? 0 }}
                    </span>
                  </div>
                  <div class="fw-semibold mt-1">Đơn đã đặt</div>
                </a>
              </div>
            </div>
            <div class="col-6">
              <div class="card border-0 shadow-sm py-3">
                <a href="{{ route('profile.orders', ['status' => 'shipping']) }}" class="text-decoration-none text-dark d-block">
                  <div class="position-relative d-inline-block mb-2">
                    <i class="fas fa-truck-fast fa-2x"></i>
                    <span class="badge bg-warning text-dark position-absolute top-0 start-100 translate-middle rounded-pill">
                      {{ $shippingCount ?? 0 }}
                    </span>
                  </div>
                  <div class="fw-semibold mt-1">Đơn đang giao</div>
                </a>
              </div>
            </div>
            <div class="col-6">
              <div class="card border-0 shadow-sm py-3">
                <a href="{{ route('profile.orders', ['status' => 'delivered']) }}" class="text-decoration-none text-dark d-block">
                  <div class="position-relative d-inline-block mb-2">
                    <i class="fas fa-clipboard fa-2x"></i>
                    <span class="badge bg-success position-absolute top-0 start-100 translate-middle rounded-pill">
                      {{ $deliveredCount ?? 0 }}
                    </span>
                  </div>
                  <div class="fw-semibold mt-1">Đơn đã giao</div>
                </a>
              </div>
            </div>
          </div>

          <div class="mt-4 text-center">
            <form action="{{ route('client.auth.logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-outline-danger w-100 py-2 fw-semibold">
                <i class="fas fa-right-from-bracket me-1"></i> Đăng xuất
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Cột phải --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0">
          <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#info">
                <i class="fas fa-user me-1"></i> Thông tin cá nhân
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#address">
                <i class="fas fa-location-dot me-1"></i> Quản lý địa chỉ
              </button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#password">
                <i class="fas fa-lock me-1"></i> Đổi mật khẩu
              </button>
            </li>
          </ul>
        </div>

        <div class="card-body tab-content">
          {{-- Thông tin cá nhân --}}
          <div class="tab-pane fade show active" id="info">
            <form action="{{ route('profile.update') }}" method="POST" class="row g-3">
              @csrf
              <div class="col-md-6">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="full_name" class="form-control"
                       value="{{ old('full_name', auth()->user()->full_name) }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="date_of_birth" class="form-control"
                       value="{{ old('date_of_birth', optional(auth()->user()->date_of_birth)->format('Y-m-d')) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', auth()->user()->email) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone_number" class="form-control"
                       value="{{ old('phone_number', auth()->user()->phone_number) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select">
                  <option value="" @selected(!auth()->user()->gender)>Chưa xác định</option>
                  <option value="female" @selected(auth()->user()->gender === 'female')>Nữ</option>
                  <option value="male" @selected(auth()->user()->gender === 'male')>Nam</option>
                  <option value="other" @selected(auth()->user()->gender === 'other')>Khác</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Trạng thái tài khoản</label>
                <input type="text" class="form-control" value="{{ $accountStatus }}" disabled>
              </div>
              <div class="col-12 text-end">
                <button class="btn btn-success">
                  <i class="fas fa-save me-1"></i> Lưu thay đổi
                </button>
              </div>
            </form>
          </div>

          {{-- Quản lý địa chỉ --}}
          <div class="tab-pane fade" id="address">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-semibold mb-0">Địa chỉ giao hàng</h6>
              <button class="btn btn-sm btn-success" type="button" data-bs-toggle="collapse"
                      data-bs-target="#addAddressForm">
                <i class="fas fa-plus"></i> Thêm địa chỉ mới
              </button>
            </div>

            <div class="collapse" id="addAddressForm">
              <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                  <form method="POST" action="{{ route('profile.addresses.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                      <label class="form-label">Tên địa chỉ</label>
                      <input name="label" class="form-control" value="{{ old('label') }}">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Người nhận</label>
                      <input name="receiver_name" class="form-control"
                             value="{{ old('receiver_name', auth()->user()->full_name) }}" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Số điện thoại</label>
                      <input name="receiver_phone" class="form-control"
                             value="{{ old('receiver_phone', auth()->user()->phone_number) }}" required>
                    </div>
                    <div class="col-12">
                      <label class="form-label">Email</label>
                      <input name="receiver_email" type="email" class="form-control"
                             value="{{ old('receiver_email', auth()->user()->email) }}">
                    </div>
                    <div class="col-12">
                      <label class="form-label">Địa chỉ cụ thể</label>
                      <input name="address_line" class="form-control"
                             placeholder="Số nhà, tên đường..." required>
                    </div>

                    {{-- ✅ Phường / Xã (chỉ 1 input hidden duy nhất) --}}
                    <div class="col-12 position-relative">
                      <label class="form-label">Phường / Xã</label>
                      <input type="text" id="wardInput" class="form-control"
                             placeholder="Nhập tên phường/xã..." autocomplete="off" required>
                      <input type="hidden" name="ward_code" id="wardCode">
                      <ul id="wardSuggestions" class="list-group position-absolute w-100 shadow-sm"
                          style="max-height:200px;overflow-y:auto;z-index:1000;display:none;"></ul>
                    </div>

                    <div class="col-12">
                      <label class="form-label">Ghi chú giao hàng</label>
                      <input name="note" class="form-control">
                    </div>

                    <div class="col-12 form-check">
                      <input class="form-check-input" type="checkbox" name="is_default" value="1">
                      <label class="form-check-label">Đặt làm địa chỉ mặc định</label>
                    </div>

                    <div class="col-12">
                      <button class="btn btn-success w-100">
                        <i class="fas fa-save me-1"></i> Lưu địa chỉ
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            {{-- Danh sách địa chỉ --}}
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0">Danh sách địa chỉ</h6>
              </div>
              <div class="card-body">
                @forelse($addresses as $address)
                  <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="fw-semibold d-flex align-items-center gap-2">
                          {{ $address->label ?? 'Địa chỉ' }}
                          @if($address->is_default)
                            <span class="badge bg-success">Mặc định</span>
                          @endif
                        </div>
                        <div class="text-muted small">{{ $address->receiver_name }} · {{ $address->receiver_phone }}</div>
                        <div>{{ $address->address_line }}, {{ $address->ward_name }}, {{ $address->district_name }}</div>
                      </div>
                      <form method="POST" action="{{ route('profile.addresses.delete', $address) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </div>
                @empty
                  <p class="text-muted mb-0 text-center">Bạn chưa có địa chỉ giao hàng nào.</p>
                @endforelse
              </div>
            </div>
          </div>

          {{-- Đổi mật khẩu --}}
          <div class="tab-pane fade" id="password">
            <form action="{{ route('profile.password.update') }}" method="POST" class="row g-3">
              @csrf
              <div class="col-12">
                <label class="form-label">Mật khẩu hiện tại</label>
                <input type="password" name="current_password" class="form-control" placeholder="Nhập mật khẩu hiện tại" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Mật khẩu mới</label>
                <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Xác nhận mật khẩu mới</label>
                <input type="password" name="new_password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
              </div>
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-success fw-semibold">
                  <i class="fas fa-key me-1"></i> Đổi mật khẩu
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
  const wardInput = document.getElementById('wardInput');
  const wardCode = document.getElementById('wardCode');
  const suggestionBox = document.getElementById('wardSuggestions');
  let wards = [];

  try {
    const res = await fetch('/api/hcm/wards');
    const data = await res.json();
    wards = data.wards || [];
  } catch (e) {
    console.error('Không tải được danh sách phường/xã', e);
  }

  wardInput.addEventListener('input', e => {
    const kw = e.target.value.trim().toLowerCase();
    suggestionBox.innerHTML = '';
    if (!kw) return (suggestionBox.style.display = 'none');
    const filtered = wards.filter(w => w.name.toLowerCase().includes(kw)).slice(0, 10);
    filtered.forEach(w => {
      const li = document.createElement('li');
      li.className = 'list-group-item list-group-item-action';
      li.textContent = w.name;
      li.onclick = () => {
        wardInput.value = w.name;
        wardCode.value = w.code;
        suggestionBox.style.display = 'none';
        console.log('✅ Đã chọn:', w);
      };
      suggestionBox.appendChild(li);
    });
    suggestionBox.style.display = 'block';
  });

  document.addEventListener('click', e => {
    if (!suggestionBox.contains(e.target) && e.target !== wardInput)
      suggestionBox.style.display = 'none';
  });

  const form = wardInput.closest('form');
  form.addEventListener('submit', e => {
    console.log('🧾 Gửi form với ward_code =', wardCode.value);
    if (!wardCode.value) {
      e.preventDefault();
      alert('Vui lòng chọn phường/xã hợp lệ từ danh sách.');
    }
  });
});
</script>
@endpush
