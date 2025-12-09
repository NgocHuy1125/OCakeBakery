@extends('layouts.admin.master')

@section('title', 'Quản lý nhập hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Phiếu nhập hàng</h4>
  <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createRestockModal">
    <i class="bx bx-plus"></i> Tạo phiếu nhập
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="table" class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Mã phiếu</th>
            <th>Ngày nhập</th>
            <th>Tổng tiền</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($receipts as $receipt)
          @php
            $totalCost = $receipt->total_cost > 0 ? $receipt->total_cost : ($receipt->line_total_sum ?? 0);
          @endphp
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="fw-semibold">{{ $receipt->receipt_code }}</td>
            <td>{{ $receipt->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ number_format($totalCost, 0, ',', '.') }} ₫</td>
            <td class="text-end">
              <a href="{{ route('admin.restock.show', $receipt->id) }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-eye me-1"></i> Chi tiết
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              <i class="bx bx-cube-alt fs-3 mb-1 d-block"></i> Chưa có phiếu nhập nào.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal tạo phiếu nhập -->
<div class="modal fade" id="createRestockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.restock.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tạo phiếu nhập h?ng m?i</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Ghi chú (nếu có)</label>
            <input type="text" name="note" class="form-control" placeholder="Nhập ghi chú nội bộ...">
          </div>

          <div class="table-responsive">
            <table class="table table-bordered align-middle" id="restockTable">
              <thead class="table-light">
                <tr>
                  <th style="width:40%">Sản phẩm</th>
                  <th class="text-center">Số lượng</th>
                  <th class="text-center">Đơn giá</th>
                  <th class="text-center">Thành tiền</th>
                  <th class="text-center">Xóa</th>
                </tr>
              </thead>
              <tbody id="restockItems">
                <tr>
                  <td>
                    <select name="products[]" class="form-select" required>
                      <option value="">-- Chọn sản phẩm --</option>
                      @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                      @endforeach
                    </select>
                  </td>
                  <td><input type="number" name="quantities[]" class="form-control text-center" value="1" min="1"></td>
                  <td><input type="number" name="unit_prices[]" class="form-control text-end" step="100" min="0" value="0"></td>
                  <td class="text-end fw-semibold line-total">0 ₫</td>
                  <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="fas fa-trash-alt"></i></button></td>
                </tr>
              </tbody>
            </table>
          </div>

          <button type="button" class="btn btn-outline-success mt-2" id="addRowBtn">
            <i class="bx bx-plus"></i> Thêm sản phẩm
          </button>

          <div class="text-end mt-3">
            <h5 class="fw-bold">Tổng cộng: <span id="totalAmount">0 ₫</span></h5>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-success">Lưu phiếu nhập</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const tbody = document.getElementById('restockItems');
  const addRowBtn = document.getElementById('addRowBtn');
  const totalDisplay = document.getElementById('totalAmount');

  const formatMoney = (n) => (n || 0).toLocaleString('vi-VN') + ' ₫';

  const updateTotals = () => {
    let total = 0;
    tbody.querySelectorAll('tr').forEach(row => {
      const qty = parseFloat(row.querySelector('input[name="quantities[]"]').value) || 0;
      const price = parseFloat(row.querySelector('input[name="unit_prices[]"]').value) || 0;
      const lineTotal = qty * price;
      row.querySelector('.line-total').textContent = formatMoney(lineTotal);
      total += lineTotal;
    });
    totalDisplay.textContent = formatMoney(total);
  };

  tbody.addEventListener('input', updateTotals);

  addRowBtn.addEventListener('click', () => {
    const template = tbody.querySelector('tr');
    const newRow = template.cloneNode(true);
    newRow.querySelector('select').value = '';
    newRow.querySelector('input[name="quantities[]"]').value = 1;
    newRow.querySelector('input[name="unit_prices[]"]').value = 0;
    newRow.querySelector('.line-total').textContent = formatMoney(0);
    tbody.appendChild(newRow);
    updateTotals();
  });

  tbody.addEventListener('click', (event) => {
    if (event.target.closest('.remove-row')) {
      if (tbody.querySelectorAll('tr').length > 1) {
        event.target.closest('tr').remove();
        updateTotals();
      }
    }
  });

  updateTotals();
});
</script>
@endpush
