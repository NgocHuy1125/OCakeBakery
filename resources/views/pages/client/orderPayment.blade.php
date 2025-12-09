@extends('layouts.client.master')

@section('title', 'Thanh Toán Đơn Hàng')

@section('content')

<div class="container my-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home" class="nav-link text-success">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/cart" class="nav-link text-success">Giỏ hàng</a></li>
            <li class="breadcrumb-item"><a href="/checkoutInformation">Thông tin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-7">
            <h1 class="h2 text-success fw-bold mb-4">Xác Nhận & Thanh Toán</h1>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Thông tin nhận hàng</h6>
                        <p class="mb-0"><strong>Nguyễn Văn A</strong> - 0987654321</p>
                        <p class="mb-0">123 Đường ABC, Phường 4, Quận 5, TP.HCM</p>
                    </div>
                    <a href="#">Thay đổi</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                 <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">Chọn phương thức thanh toán</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <label class="list-group-item list-group-item-action d-flex align-items-center">
                            <input class="form-check-input mt-0 me-3" type="radio" name="paymentMethod" value="cod" checked>
                            <i class="fas fa-truck fa-lg me-3 text-muted"></i>
                            <div>
                                <div class="fw-bold">Thanh toán khi nhận hàng (COD)</div>
                                <small class="text-muted">Thanh toán tiền mặt cho nhân viên giao hàng.</small>
                            </div>
                        </label>
                        <label class="list-group-item list-group-item-action d-flex align-items-center">
                            <input class="form-check-input mt-0 me-3" type="radio" name="paymentMethod" value="momo">
                            <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" width="25" class="me-3">
                            <div>
                                <div class="fw-bold">Ví điện tử Momo</div>
                                <small class="text-muted">Thanh toán qua ứng dụng Momo.</small>
                            </div>
                        </label>
                        <label class="list-group-item list-group-item-action d-flex align-items-center">
                            <input class="form-check-input mt-0 me-3" type="radio" name="paymentMethod" value="zalopay">
                            <img src="https://cdn.haitrieu.com/wp-content/uploads/2022/10/Logo-ZaloPay-Square.png" width="25" class="me-3">
                            <div>
                                <div class="fw-bold">Ví điện tử ZaloPay</div>
                                <small class="text-muted">Thanh toán qua ứng dụng ZaloPay.</small>
                            </div>
                        </label>
                        <label class="list-group-item list-group-item-action d-flex align-items-center">
                            <input class="form-check-input mt-0 me-3" type="radio" name="paymentMethod" value="bank">
                            <i class="fas fa-university fa-lg me-3 text-muted"></i>
                            <div>
                                <div class="fw-bold">Chuyển khoản ngân hàng</div>
                                <small class="text-muted">Thông tin chuyển khoản sẽ được hiển thị sau khi đặt hàng.</small>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">Tổng Cộng</h5>
                </div>
                <div class="card-body">
                     <div class="d-flex justify-content-between">
                        <span>Tạm tính</span>
                        <span>400,000 đ</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Phí vận chuyển</span>
                        <span>30,000 đ</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span class="text-success">Tổng thanh toán</span>
                        <span class="text-success">430,000 đ</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-white p-3">
                    <button class="btn btn-success btn-lg w-100 fw-bold">
                        <i class="fas fa-check-circle me-2"></i> Hoàn Tất Đặt Hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection