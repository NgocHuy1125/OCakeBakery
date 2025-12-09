@extends('layouts.admin.master')

@section('title', 'Tin nhắn liên hệ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">Tin nhắn liên hệ</h4>
</div>

@if($contacts->isEmpty())
  <div class="alert alert-info mb-0 shadow-sm">
    <i class="fas fa-inbox me-2"></i>Hiện chưa có tin nhắn nào được gửi.
  </div>
@else
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="table" class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Số điện thoại</th>
              <th>Nội dung</th>
              <th>Trạng thái</th>
              <th>Ngày gửi</th>
              <th class="text-center" style="width: 120px;">Hành động</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($contacts as $contact)
            @php
              $statusLabels = [
                'pending' => ['text' => 'Chờ xử lý', 'class' => 'bg-warning text-dark'],
                'answered' => ['text' => 'Đã phản hồi', 'class' => 'bg-success'],
                'closed' => ['text' => 'Đã đóng', 'class' => 'bg-secondary'],
              ];
              $status = $statusLabels[$contact->status] ?? ['text' => ucfirst($contact->status), 'class' => 'bg-secondary'];
            @endphp
            <tr class="{{ $contact->status === 'pending' ? 'fw-semibold bg-light' : '' }}">
              <td>{{ $loop->iteration }}</td>
              <td>{{ $contact->full_name }}</td>
              <td>{{ $contact->email }}</td>
              <td>{{ $contact->phone_number ?? '—' }}</td>

              <td>
                <a href="#" 
                   class="text-decoration-none text-muted small show-message" 
                   data-bs-toggle="modal" 
                   data-bs-target="#messageModal" 
                   data-name="{{ $contact->full_name }}"
                   data-email="{{ $contact->email }}"
                   data-phone="{{ $contact->phone_number ?? '—' }}"
                   data-date="{{ $contact->created_at->format('d/m/Y H:i') }}"
                   data-message="{{ $contact->message }}">
                   {{ Str::limit($contact->message, 50, '...') }}
                </a>
              </td>

              <td>
                <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
              </td>
              <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
              <td class="text-center">
                @if($contact->status === 'pending')
                  <form action="{{ route('admin.contacts.markAsRead', $contact) }}" method="POST" class="d-inline">
                    @csrf @method('PATCH')
                    <button class="btn btn-sm btn-outline-info" title="Đánh dấu đã xem">
                      <i class="fas fa-eye"></i>
                    </button>
                  </form>
                @endif
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa tin nhắn này?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" title="Xóa tin nhắn">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endif

<!-- Modal hiển thị chi tiết -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel"><i class="fas fa-envelope me-2"></i>Chi tiết tin nhắn</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Họ tên:</strong> <span id="modalName"></span></p>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Số điện thoại:</strong> <span id="modalPhone"></span></p>
        <p><strong>Gửi lúc:</strong> <span id="modalDate"></span></p>
        <hr>
        <p class="mb-0"><strong>Nội dung:</strong></p>
        <p id="modalMessage" class="mt-2 text-muted"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-xmark me-1"></i>Đóng</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show-message').forEach(link => {
      link.addEventListener('click', function () {
        document.getElementById('modalName').textContent = this.dataset.name;
        document.getElementById('modalEmail').textContent = this.dataset.email;
        document.getElementById('modalPhone').textContent = this.dataset.phone;
        document.getElementById('modalDate').textContent = this.dataset.date;
        document.getElementById('modalMessage').textContent = this.dataset.message;
      });
    });
  });
</script>
@endpush
