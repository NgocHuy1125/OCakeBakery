<?php $__env->startSection('content'); ?>
<div class="auth-page">
  <div class="auth-card">
    <div class="row g-0 align-items-stretch">
      <div class="col-lg-5">
        <div class="auth-card__aside h-100">
          <div>
            <span class="auth-card__badge"><i class="fas fa-seedling"></i> Thành viên mới</span>
            <h2 class="auth-card__headline">Gia nhập hội bánh ngọt</h2>
            <p class="auth-card__text">Tạo tài khoản để đặt bánh nhanh chóng, lưu địa chỉ giao hàng và nhận ưu đãi mỗi tuần.</p>
          </div>
          <ul class="auth-card__benefits">
            <li><i class="fas fa-cake-candles"></i> Bộ sưu tập bánh mới mỗi ngày</li>
            <li><i class="fas fa-heart"></i> Lưu món yêu thích và đặt lại nhanh</li>
            <li><i class="fas fa-ticket"></i> Voucher tri ân & quà sinh nhật</li>
          </ul>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="auth-card__body">
          <div class="auth-card__heading">
            <div>
              <h1 class="auth-card__title">Đăng ký tài khoản</h1>
              <p class="auth-card__subtitle">Tạo tài khoản để đặt mua sản phẩm</p>
            </div>
          </div>

          <?php if($errors->any()): ?>
          <div class="alert alert-danger small mb-4">Vui lòng kiểm tra lại thông tin vừa nhập.</div>
          <?php endif; ?>

          <form action="<?php echo e(route('client.register.process')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-group mb-3">
              <div class="input-wrapper">
                <input type="text" name="full_name" value="<?php echo e(old('full_name')); ?>" required>
                <label>Họ và tên</label>
              </div>
            </div>

            <div class="form-group mb-3">
              <div class="input-wrapper">
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required>
                <label>Email</label>
              </div>
            </div>

            <div class="form-group mb-3">
              <div class="input-wrapper">
                <input type="text" name="phone_number" value="<?php echo e(old('phone_number')); ?>" required>
                <label>Số điện thoại</label>
              </div>
            </div>

            <div class="form-group mb-3">
              <div class="input-wrapper">
                <input type="password" id="register-password" name="password" required>
                <label>Mật khẩu</label>
              </div>
            </div>

            <div class="form-group mb-4">
              <div class="input-wrapper">
                <input type="password" name="password_confirmation" required>
                <label>Nhập lại mật khẩu</label>
              </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2">
              <i class="fas fa-user-plus me-2"></i> Đăng ký ngay
            </button>
          </form>

          <div class="auth-card__meta text-center mt-3">
            Đã có tài khoản? <a href="<?php echo e(route('login')); ?>">Đăng nhập</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/client/auth/registerPage.blade.php ENDPATH**/ ?>