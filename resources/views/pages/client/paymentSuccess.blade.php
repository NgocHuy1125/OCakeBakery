@extends('layouts.client.master')

@section('title', 'Thanh toán thành công')

@section('content')
<div class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="nav-link text-success">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán thành công</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="display-5 text-success mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="fw-bold text-success mb-3">Bạn đã thanh toán thành công!</h3>
                    <p class="text-muted">Chúng tôi đã ghi nhận giao dịch và sẽ chuẩn bị đơn hàng để giao tới bạn trong thời gian sớm nhất.</p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('profile.orders') }}" class="btn btn-success fw-bold">
                            Xem đơn hàng của tôi
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
