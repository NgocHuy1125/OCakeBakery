@extends('layouts.client.master')

@section('title', 'Liên hệ')

@section('content')
<div class="stores-page">
    <!-- Hero -->
    <section class="hero-banner mb-5 position-relative text-center text-white rounded-4 overflow-hidden hero-bg-bakery">
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="container-fluid hero-content position-relative p-5">
        <h1 class="fw-bold display-5 mb-3 text-uppercase">Cửa hàng</h1>
        <p class="lead mb-4 col-md-8 col-12 mx-auto">
            Hệ thống cửa hàng Tiệm bánh Kim Loan trên toàn quốc, mang đến trải nghiệm mua sắm tiện lợi và nhanh chóng cho khách hàng.
        </p>
        <a href="#stores" class="btn btn-light btn-lg px-3">
            Khám phá ngay <i class="fas fa-arrow-up-right-from-square ms-1"></i>
        </a>
        </div>
    </section>

    <section class="pb-5" id="stores">
        <div class="text-center mb-4">
            <h2 class="fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2 w-max-content mx-auto">Hệ thống cửa hàng</h2>
            <p class="text-muted">Những sản phẩm để lại ấn tượng sâu sắc cho khách hàng và biết đến Kim Loan</p>
        </div>
        <div class="row g-4 align-items-stretch">
            <div class="col-md-6 card p-4 border rounded-4 shadow-sm h-100">
                <h2 class="text-success fw-semibold">Tiệm Bánh Kim Loan - Độc Lập</h2>
                <p class="my-1"><i class="fas fa-location-dot me-1"></i> Địa chỉ: 90 Độc Lập, Tân Phú, TP.HCM</p>
                <p class="my-1"><i class="fas fa-clock me-1"></i> Giờ mở cửa: 14h - 21h</p>
                <p class="my-1"><i class="fas fa-phone me-1"></i> Điện thoại: 0862 427 713</p>
                <p class="my-1"><i class="fas fa-envelope me-1"></i> Email cửa hàng: cn.doclap@tiembanhkimloan.com</p>
            </div>
            <div class="col-md-6 h-100">
                <div class="ratio ratio-16x9 rounded-4 shadow-sm overflow-hidden">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.251328455061!2d106.63245417457506!3d10.792052858906246!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752eaca77a761f%3A0xed8d54216ae406e7!2zVGnhu4dtIELDoW5oIEtpbSBMb2Fu!5e0!3m2!1sen!2s!4v1760809628868!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection