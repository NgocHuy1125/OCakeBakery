<footer class="bg-success text-white mt-auto">
  <div class="container pt-5 pb-3">
    {{-- Wrapper flex cho desktop --}}
    <div class="row g-4 d-flex justify-content-between">
      {{-- Logo + giới thiệu --}}
      <div class="col-12 col-md-4">
        <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 mb-3 text-decoration-none text-white">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="rounded" style="height:40px;width:40px;object-fit:cover">
          <span class="fw-bold fs-5">Tiệm bánh Kim Loan</span>
        </a>
        <p class="small mb-2">
          Chuyên cung cấp sỉ lẻ các loại bánh ngọt, nhận đặt bánh và giao hàng nhanh chóng trong vòng 2 tiếng đồng hồ tại TP.HCM
        </p>
        <ul class="list-unstyled small mb-0">
          <li class="py-1"><i class="fa-solid fa-location-dot me-2"></i>90 Độc Lập, Tân Phú, TP.HCM</li>
          <li class="py-1"><i class="fa-solid fa-phone me-2"></i>0862 427 713</li>
          <li class="py-1"><i class="fa-solid fa-envelope me-2"></i>contact@tiembanhkimloan.com</li>
        </ul>
      </div>

      {{-- Hỗ trợ --}}
      <div class="col-6 col-md-3">
        <h5 class="fw-semibold mb-3">Hỗ trợ mua hàng</h5>
        <ul class="list-unstyled small">
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="{{ url('/shipping') }}">Giao hàng</a></li>
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="{{ url('/refund') }}">Đổi trả</a></li>
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="{{ url('/faq') }}">FAQ</a></li>
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="{{ url('/size-guide') }}">Kích thước bánh</a></li>
        </ul>
      </div>

      {{-- Mạng xã hội --}}
      <div class="col-6 col-md-3">
        <h5 class="fw-semibold mb-3">Mạng xã hội</h5>
        <ul class="list-unstyled small">
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="#"><i class="fa-brands fa-facebook me-2"></i>Facebook</a></li>
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="#"><i class="fa-brands fa-instagram me-2"></i>Instagram</a></li>
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="#"><i class="fa-brands fa-tiktok me-2"></i>TikTok</a></li>
          <li class="py-1"><a class="link-light link-underline-opacity-0" href="#"><i class="fa-solid fa-globe me-2"></i>Website</a></li>
        </ul>
      </div>
    </div>

    <hr class="border-light mt-4 mb-3">
    <p class="text-center small mb-0">Copyright © {{ date('Y') }} Tiệm Bánh Kim Loan. All rights reserved.</p>
  </div>
</footer>
