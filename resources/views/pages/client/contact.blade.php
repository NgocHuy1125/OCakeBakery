@extends('layouts.client.master')

@section('title', 'Liên hệ')

@section('content')
<div class="contacts-page">
    <!-- Hero -->
    <section class="hero-banner mb-5 position-relative text-center text-white rounded-4 overflow-hidden hero-bg-bakery">
        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="container-fluid hero-content position-relative p-5">
        <h1 class="fw-bold display-5 mb-3 text-uppercase">Liên hệ</h1>
        <p class="lead mb-4 col-md-8 col-12 mx-auto">
            Kết nối với Tiệm bánh Kim Loan để chia sẻ ý kiến, đặt câu hỏi hoặc nhận hỗ trợ về sản phẩm và dịch vụ của chúng tôi.
        </p>
        <a href="#contacts" class="btn btn-light btn-lg px-3">
            Liên hệ ngay <i class="fas fa-arrow-up-right-from-square ms-1"></i>
        </a>
        </div>
    </section>

    <section class="pb-5" id="contacts">
      <div class="row justify-content-between g-4">
        <div class="col-md-6 mb-3">
          <div class="contact-details text-justify">
            <h2 class="fw-bold text-uppercase bg-success text-white rounded-pill px-4 py-2 w-max-content mb-3">Liên hệ với chúng tôi</h2>
            <p>Chúng tôi luôn trân trọng từng chia sẻ từ bạn.
            Dù là một lời hỏi thăm, góp ý nhỏ hay mong muốn hợp tác, hãy để lại lời nhắn và đội ngũ chúng tôi sẽ phản hồi bạn trong thời gian sớm nhất.</p>
            <hr>
            <div class="d-flex align-items-start gap-3 mb-3">
              <i class="fas fa-location-dot fa-fw fs-4 text-success mt-1"></i>
                <div>
                <h6 class="fw-bold mb-0">Cửa hàng chính</h6><a href="https://www.google.com/maps/place/Ti%E1%BB%87m+B%C3%A1nh+Kim+Loan/@10.7920529,106.6324542,17z/data=!3m1!4b1!4m6!3m5!1s0x31752eaca77a761f:0xed8d54216ae406e7!8m2!3d10.7920476!4d106.6350291!16s%2Fg%2F1hc2jsdr2?entry=ttu&g_ep=EgoyMDI1MTAxNC4wIKXMDSoASAFQAw%3D%3D" class="text-muted text-decoration-none">90 Độc Lập, Tân Phú, TP.HCM</a>
                </div>
            </div>
            <div class="d-flex align-items-start gap-3 mb-3">
              <i class="fas fa-phone-alt fa-fw fs-4 text-success mt-1"></i>
                <div>
                <h6 class="fw-bold mb-0">Hotline hỗ trợ</h6><a href="tel:0862427713" class="text-muted text-decoration-none">0862 427 713</a>
                </div>
            </div>
            <div class="d-flex align-items-start gap-3 mb-3">
              <i class="fas fa-envelope fa-fw fs-4 text-success mt-1"></i>
                <div>
                <h6 class="fw-bold mb-0">Email liên hệ</h6><a href="mailto:contact@tiembanhkimloan.com" class="text-muted text-decoration-none">contact@tiembanhkimloan.com</a>
                </div>
            </div>
          </div>
        </div>

        <div class="col-md-5 bg-white p-4 rounded-4 shadow-sm">
          <form method="POST" action="{{ url('/contact/send') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="fas fa-user me-1"></i> Họ và tên</label>
              <input type="text" name="name" class="form-control" placeholder="Nhập họ và tên" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="fas fa-phone me-1"></i> Số điện thoại</label>
              <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="fas fa-envelope me-1"></i> Email</label>
              <input type="email" name="email" class="form-control" placeholder="Nhập địa chỉ email" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="fas fa-message me-1"></i> Nội dung</label>
              <textarea name="message" class="form-control" rows="4" placeholder="Viết tin nhắn..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100"><i class="far fa-paper-plane me-2"></i>Gửi liên hệ</button>
          </form>
        </div>

        
      </div>
    </section>
</div>
@endsection
