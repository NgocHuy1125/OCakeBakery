@extends('layouts.admin.master')

@section('title', 'Chi tiết phiếu nhập #' . $receipt->receipt_code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">Chi tiết phiếu nhập #{{ $receipt->receipt_code }}</h4>
  <div>
    <a href="{{ route('admin.restock.index') }}" class="btn btn-outline-dark">
      <i class="fas fa-angle-left me-1"></i> Quay lại
    </a>
    <button onclick="window.print()" class="btn btn-primary">
      <i class="bx bx-printer"></i> In phiếu
    </button>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">

    {{-- Thông tin chung --}}
    <div class="row mb-3">
      <div class="col-md-6">
        <h6 class="fw-bold mb-2">Thông tin phiếu nhập</h6>
        <p class="mb-1">
          <strong>Mã phiếu:</strong> {{ $receipt->receipt_code }}<br>
          <strong>Ngày tạo:</strong> {{ $receipt->created_at->format('d/m/Y H:i') }}
        </p>
      </div>

      <div class="col-md-6 text-md-end">
        <h6 class="fw-bold mb-2">Người tạo phiếu</h6>
        <p class="mb-1">
          @if($receipt->creator)
            <small class="text-muted">{{ $receipt->creator->email ?? '' }}</small>
          @else
            <span class="text-muted">Chưa xác định</span>
          @endif
        </p>
      </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
          </tr>
        </thead>
        <tbody>
          @forelse($receipt->items as $item)
            <tr>
              <td>{{ $loop->iteration }}</td>

              <td>
                @if($item->product)
                  <div class="d-flex align-items-center">
                    <img src="{{ $item->product->primary_image_url ?? asset('images/no-image.png') }}"
                         alt="{{ $item->product->name }}" width="60" height="60"
                         class="rounded border object-fit-cover me-2"
                         onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';">
                    <div>
                      <div class="fw-semibold">{{ $item->product->name }}</div>
                      <small class="text-muted">Mã: {{ $item->product->product_code }}</small>
                    </div>
                  </div>
                @else
                  <span class="text-muted">Sản phẩm đã bị xóa</span>
                @endif
              </td>

              <td class="text-center">{{ $item->quantity }}</td>
              <td>{{ number_format($item->unit_cost, 0, ',', '.') }} ₫</td>
              <td>{{ number_format($item->line_total, 0, ',', '.') }} ₫</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-4">
                <i class="bx bx-cube-alt fs-3 d-block mb-1"></i>
                Không có sản phẩm nào trong phiếu nhập.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Tổng tiền --}}
    <div class="text-end mt-3">
      <h5 class="fw-bold mb-1">Tổng cộng: {{ number_format($displayTotal ?? $receipt->total_cost, 0, ',', '.') }} ₫</h5>
      <small class="text-muted">Đã bao gồm toàn bộ sản phẩm trong phiếu nhập.</small>
    </div>

  </div>
</div>
@endsection
