@extends('layouts.admin.master')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0 fw-bold">Quản lý đơn hàng</h4>
</div>

@if($orders->isEmpty())
  <div class="alert alert-info text-center">Chưa có đơn hàng nào.</div>
@else
  <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
    @foreach($statuses as $status => $label)
      @php
        $list = $orders->get($status);

        $badgeClass = match($status) {
          'pending' => 'bg-warning text-dark',
          'confirmed' => 'bg-info text-dark',
          'preparing' => 'bg-primary',
          'shipping' => 'bg-secondary',
          'completed' => 'bg-success',
          'cancelled' => 'bg-danger',
          default => 'bg-light text-dark'
        };
      @endphp

      <li class="nav-item" role="presentation">
        <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $status }}" 
                data-bs-toggle="tab"
                data-bs-target="#content-{{ $status }}" 
                type="button" role="tab" aria-controls="content-{{ $status }}">
          {{ $label }}
          <span class="badge ms-1 {{ $badgeClass }}">{{ $list ? $list->count() : 0 }}</span>
        </button>
      </li>
    @endforeach
  </ul>

  <div class="tab-content" id="orderTabsContent">
    @foreach($statuses as $status => $label)
      @php
        $list = $orders->get($status);

        $badgeClass = match($status) {
          'pending' => 'bg-warning text-dark',
          'confirmed' => 'bg-info text-dark',
          'preparing' => 'bg-primary',
          'shipping' => 'bg-secondary',
          'completed' => 'bg-success',
          'cancelled' => 'bg-danger',
          default => 'bg-light text-dark'
        };

        // Payment status badge colors
        $paymentBadgeColors = [
          'pending' => 'bg-warning text-dark',
          'processing' => 'bg-info text-dark',
          'paid' => 'bg-success',
          'failed' => 'bg-danger',
          'refunded' => 'bg-secondary',
        ];
      @endphp

      <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
           id="content-{{ $status }}" role="tabpanel">

        @if(!$list)
          <p class="text-muted text-center py-4">Không có đơn hàng nào.</p>
        @else

          <div class="table-responsive">
            <table id="table" class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Mã đơn hàng</th>
                  <th>Khách hàng</th>
                  <th>Tổng tiền</th>
                  <th>Thanh toán</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>

                @foreach($list as $order)
                  <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td class="fw-semibold">{{ $order->order_code }}</td>

                    <td>{{ $order->customer_name }} • {{ $order->customer_phone }}</td>

                    <td>{{ number_format($order->grand_total, 0, ',', '.') }} ₫</td>

                    {{-- PAYMENT STATUS --}}
                    <td>
                      <span class="badge {{ $paymentBadgeColors[$order->payment_status] ?? 'bg-light text-dark' }}">
                        {{ ucfirst($order->payment_status) }}
                      </span>
                    </td>

                    {{-- FULFILLMENT STATUS --}}
                    <td>
                      <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                    </td>

                    <td>
                      {{-- Xem chi tiết --}}
                      <a href="{{ route('admin.orders.show', $order->id) }}" 
                        class="btn btn-sm btn-outline-dark me-1">
                        <i class="fas fa-eye"></i>
                      </a>

                      @if($order->fulfillment_status === 'pending')
                        <form method="POST" 
                              action="{{ route('admin.orders.quickProcess', $order->id) }}" 
                              class="d-inline">
                          @csrf
                          @method('PUT')
                          <button class="btn btn-sm btn-outline-success me-1">
                            <i class="fas fa-check"></i>
                          </button>
                        </form>
                      @endif

                      {{-- Nút mở modal Cập nhật --}}
                      <button class="btn btn-sm btn-outline-primary" 
                              data-bs-toggle="modal"
                              data-bs-target="#modalOrder-{{ $order->id }}">
                        <i class="fas fa-edit"></i>
                      </button>
                  </td>

                  </tr>

                  <!-- Modal cập nhật -->
                  <div class="modal fade" id="modalOrder-{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h5 class="modal-title">Cập nhật đơn: {{ $order->order_code }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                          @csrf
                          @method('PUT')

                          <div class="modal-body">

                            {{-- Cập nhật Fulfillment --}}
                            <div class="mb-3">
                              <label class="form-label">Trạng thái đơn hàng</label>
                              <select name="fulfillment_status" class="form-select">
                                @foreach($statuses as $key => $text)
                                  <option value="{{ $key }}" 
                                          @selected($order->fulfillment_status === $key)>
                                    {{ $text }}
                                  </option>
                                @endforeach
                              </select>
                            </div>

                            {{-- Cập nhật Payment --}}
                            <div class="mb-3">
                              <label class="form-label">Trạng thái thanh toán</label>
                              <select name="payment_status" class="form-select">
                                @foreach($paymentBadgeColors as $key => $color)
                                  <option value="{{ $key }}" 
                                          @selected($order->payment_status === $key)>
                                    {{ ucfirst($key) }}
                                  </option>
                                @endforeach
                              </select>
                            </div>

                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" 
                                    data-bs-dismiss="modal">Đóng</button>

                            <button type="submit" class="btn btn-success">Lưu</button>
                          </div>

                        </form>

                      </div>
                    </div>
                  </div>

                @endforeach

              </tbody>
            </table>
          </div>

        @endif
      </div>
    @endforeach
  </div>

@endif
@endsection
