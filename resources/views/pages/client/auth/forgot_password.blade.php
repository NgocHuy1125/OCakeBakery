@extends('layouts.client.master')

@section('title', 'Quên mật khẩu')

@section('content')
<div class="auth-page">
  <div class="auth-card col-lg-6">
    <div class="auth-card__body text-center">

      <h1 class="mb-3 fw-bold">Quên mật khẩu</h1>
      <p class="text-muted mb-4">Nhập email để nhận mã xác nhận</p>

      @if (session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger small">{{ session('error') }}</div>
      @endif

      {{-- Gửi OTP --}}
      <form action="{{ route('client.password.sendOtp') }}" method="POST" id="emailForm" class="{{ session('reset_email') ? 'd-none' : '' }}">
        @csrf
        <div class="mb-3">
          <input type="email" class="form-control text-center" name="email" placeholder="Nhập email" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Lấy mã xác nhận</button>
      </form>

      {{-- Nhập OTP --}}
      <form action="{{ route('client.password.verify') }}" method="POST" id="otpForm" class="{{ session('reset_email') ? '' : 'd-none' }}">
        @csrf
        <div class="mb-3">
          <p class="fw-semibold">Nhập mã OTP 6 chữ số đã gửi đến: <span class="text-success">{{ session('reset_email') }}</span></p>
        </div>

        <div class="d-flex justify-content-center gap-2 mb-3">
          @for ($i = 0; $i < 6; $i++)
            <input type="text" maxlength="1" name="otp[]" class="otp-input form-control text-center" required>
          @endfor
        </div>

        <div class="text-center mb-3 text-danger fw-bold">
          <span id="countdown">60</span> giây
        </div>

        <button type="submit" class="btn btn-success w-100">Xác nhận</button>
      </form>

      <a href="{{ route('login') }}" class="d-block mt-3 text-decoration-none text-success"><i class="fas fa-angle-left me-1"></i> Quay lại đăng nhập</a>
    </div>
  </div>
</div>

<style>
.otp-input {
  width: 45px;
  font-size: 22px;
  text-align: center;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const otpInputs = document.querySelectorAll(".otp-input");
  otpInputs.forEach((input, index) => {
    input.addEventListener("input", () => {
      if (input.value.length === 1 && index < otpInputs.length - 1) {
        otpInputs[index + 1].focus();
      }
    });
  });

  let countdownEl = document.getElementById("countdown");
  if (countdownEl) {
    let seconds = 60;
    const timer = setInterval(() => {
      seconds--;
      countdownEl.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(timer);
        countdownEl.textContent = "Hết hạn";
      }
    }, 1000);
  }
});
</script>
@endsection
