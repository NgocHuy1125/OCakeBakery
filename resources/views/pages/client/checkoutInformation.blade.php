@extends('layouts.client.master')

@section('title', 'Thông Tin Đặt Hàng')

@section('content')

<div class="container my-5">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4 w-100">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home" class="nav-link text-success">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/cart" class="nav-link text-success">Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thông tin đặt hàng</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-7">
            <h2 class="text-success fw-bold mb-4 text-uppercase">Thông tin đơn hàng</h2>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="#" method="POST" id="infoForm">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Họ và tên người nhận</label>
                            <input type="text" class="form-control" id="fullName" required>
                        </div>
                        <div class="mb-3">
                            <label for="phoneNumber" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phoneNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                            <input type="text" class="form-control" id="address" placeholder="Số nhà, tên đường, phường/xã..." required>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control" id="note" rows="3"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm position-sticky" style="top: 20px;">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 fw-bold">Đơn Hàng Của Bạn</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://cdn.tgdd.vn/2021/08/CookRecipe/Avatar/banh-kem-bap-thumbnail-1.jpg" class="rounded me-3" style="width: 60px;">
                        <div class="flex-grow-1">
                            <span>Bánh Kem Bắp x 1</span>
                        </div>
                        <span>300,000 đ</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://cdn.tgdd.vn/2020/12/CookRecipe/Avatar/banh-mi-bo-toi-pho-mai-thumbnail-1.jpg" class="rounded me-3" style="width: 60px;">
                        <div class="flex-grow-1">
                            <span>Bánh mì bơ tỏi x 2</span>
                        </div>
                        <span>50,000 đ</span>
                    </div>
                    <hr>
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
                        <span class="text-success">Tổng cộng</span>
                        <span class="text-success">430,000 đ</span>
                    </div>
                </div>
                <div class="card-footer border-0 bg-white p-3">
                    <a href="/orderPayment"><button type="submit" form="infoForm" class="btn btn-success btn-lg w-100 fw-bold">
                        Thanh Toán</button></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection