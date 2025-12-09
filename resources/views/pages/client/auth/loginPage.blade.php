@extends('layouts.client.master')

@section('content')
<div class="auth-page">
  <div class="auth-card">
    <div class="row g-0 align-items-stretch">
      <div class="col-lg-5">
        <div class="auth-card__aside h-100">
          <div>
            <span class="auth-card__badge"><i class="fas fa-cookie-bite"></i> Tiệm bánh Kim Loan</span>
            <h2 class="auth-card__headline">Xin chào!</h2>
            <p class="auth-card__text">Đăng nhập để đặt bánh nhanh, theo dõi đơn hàng và nhận ưu đãi riêng.</p>
          </div>
          <ul class="auth-card__benefits">
            <li><i class="fas fa-clock"></i> Đặt bánh linh hoạt bất cứ lúc nào</li>
            <li><i class="fas fa-truck-fast"></i> Cập nhật trạng thái giao hàng theo thời gian thực</li>
            <li><i class="fas fa-gift"></i> Tích điểm và nhận ưu đãi thành viên</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="auth-card__body">
          <div class="auth-card__heading">
            <div>
              <h1 class="auth-card__title">Đăng nhập</h1>
              <p class="auth-card__subtitle">Đăng nhập để mua bánh và quản lý đơn hàng</p>
            </div>
          </div>

          @if (session('status'))
          <div class="alert alert-success small mb-4">{{ session('status') }}</div>
          @endif

          <form action="{{ route('client.login.process') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
              <div class="input-wrapper">
                <input type="email" name="email" value="{{ old('email') }}" required>
                <label>Email</label>
              </div>
            </div>

            <div class="form-group mb-4">
              <div class="input-wrapper">
                <input type="password" id="login-password" name="password" required>
                <label>Mật khẩu</label>
              </div>
            </div>

            <div class="form-options mb-3 d-flex justify-content-between align-items-center">
              <label class="remember-wrapper mb-0">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="checkbox-label"><span class="checkmark"></span> Ghi nhớ đăng nhập</span>
              </label>
              <a href="{{ route('client.password.forgot') }}" class="text-success small">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2">
              <i class="fas fa-right-to-bracket me-2"></i> Đăng nhập ngay
            </button>
          </form>

          <div class="auth-card__meta text-center mt-3">
            Chưa có tài khoản? <a href="{{ route('client.auth.register') }}">Tạo tài khoản ngay</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
