@extends('layouts.client.master')

@section('title', 'Địa chỉ giao hàng')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Địa chỉ giao hàng</h4>
    <a href="{{ route('profile.home') }}" class="btn btn-link text-decoration-none">← Quay lại hồ sơ</a>
  </div>

  <div class="row g-4">
    {{-- 🧾 Thêm địa chỉ mới --}}
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0">Thêm địa chỉ mới</h5>
        </div>
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
              <input name="address_line" class="form-control" placeholder="Số nhà, tên đường..." required>
            </div>

            {{-- 🏘️ Phường / Xã autocomplete --}}
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

    {{-- 📦 Danh sách địa chỉ --}}
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0">Danh sách địa chỉ</h5>
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
                  <div class="text-muted small">
                    {{ $address->receiver_name }} · {{ $address->receiver_phone }}
                  </div>
                  <div>{{ $address->address_line }}, {{ $address->ward_name }}, {{ $address->district_name }}</div>
                  @if($address->note)
                    <div class="small fst-italic text-secondary">
                      Ghi chú: {{ $address->note }}
                    </div>
                  @endif
                </div>
                <form method="POST" action="{{ route('profile.addresses.delete', $address) }}"
                      onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">Xóa</button>
                </form>
              </div>
            </div>
          @empty
            <p class="text-muted mb-0 text-center">Bạn chưa có địa chỉ giao hàng nào.</p>
          @endforelse
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
    // ✅ Lấy danh sách phường/xã từ API Laravel
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
    if (!wardCode.value) {
      e.preventDefault();
      alert('Vui lòng chọn phường/xã hợp lệ từ danh sách.');
    }
  });
});
</script>
@endpush
