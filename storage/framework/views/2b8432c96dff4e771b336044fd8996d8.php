<?php
    $adminUser = Auth::user();
    $adminNotifications = ($notifications ?? collect());
    $adminUnread = $adminNotifications->whereNull('read_at')->count();
?>

<!--  Header Start -->
<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="d-flex align-items-center w-100 justify-content-between">
      <ul class="navbar-nav align-items-center">
        <li class="nav-item d-block d-xl-none">
          <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
            <i class="ti ti-menu-2"></i>
          </a>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-3 ms-auto">
        <div class="dropdown">
          <a class="nav-link position-relative text-body" href="#" id="adminNotificationDropdown" data-bs-toggle="dropdown" data-bs-offset="0,8" aria-expanded="false">
            <i class="ti ti-bell font-lg"></i>
            <?php if($adminUnread > 0): ?>
              <span class="badge bg-danger position-absolute top-0 start-100 translate-middle notification-badge" data-notification-count data-count="<?php echo e($adminUnread); ?>"><?php echo e($adminUnread); ?></span>
            <?php endif; ?>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up p-0 notification-dropdown-menu" aria-labelledby="adminNotificationDropdown" style="width: 320px; max-height: 360px; overflow-y: auto;">
            <div class="dropdown-header bg-light fw-bold text-center py-2 d-flex justify-content-between align-items-center px-3">
              <span>Thông báo</span>
              <?php if($adminUnread > 0): ?>
                <button class="btn btn-sm btn-outline-secondary mark-all-btn" data-mark-all-url="<?php echo e(route('notifications.markAll')); ?>">Đánh dấu tất cả</button>
              <?php endif; ?>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $adminNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notify): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <a href="<?php echo e($notify->link ?? '#'); ?>" class="dropdown-item small py-2 border-bottom d-flex align-items-start notification-item" data-mark-url="<?php echo e(route('notifications.markRead', $notify->id)); ?>">
                <i class="ti ti-point-filled notification-dot text-<?php echo e($notify->read_at ? 'secondary' : 'primary'); ?> me-2 mt-1" style="font-size:10px;"></i>
                <div>
                  <div class="fw-semibold"><?php echo e($notify->title); ?></div>
                  <div class="text-muted small"><?php echo e($notify->created_at->diffForHumans()); ?></div>
                </div>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <div class="dropdown-item text-center text-muted py-3">Chưa có thông báo nào</div>
            <?php endif; ?>
          </div>
        </div>
        <span class="header-divider d-none d-md-inline-block"></span>
        <div class="dropdown">
          <a class="nav-link d-flex align-items-center gap-2 px-2 py-0 h-100" href="#" id="adminProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo e($adminUser?->avatar_url ? asset($adminUser->avatar_url) : asset('admin/assets/images/profile/user-1.jpg')); ?>" alt="avatar" width="35" height="35" class="rounded-circle flex-shrink-0">
            <div class="d-flex flex-column justify-content-center text-start lh-sm">
              <h6 class="mb-0"><?php echo e($adminUser?->full_name ?? $adminUser?->email ?? 'Quản trị viên'); ?></h6>
              <span class="badge bg-primary-subtle text-primary text-uppercase"><?php echo e($adminUser?->role ?? 'admin'); ?></span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="adminProfileDropdown">
            <div class="message-body">
              <a href="<?php echo e(route('profile.home')); ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-user fs-6"></i>
                <p class="mb-0 fs-3">Hồ sơ cá nhân</p>
              </a>
              <a href="<?php echo e(route('profile.orders')); ?>" class="d-flex align-items-center gap-2 dropdown-item">
                <i class="ti ti-receipt fs-6"></i>
                <p class="mb-0 fs-3">Đơn của tôi</p>
              </a>
              <hr class="my-1">
              <form action="<?php echo e(route('client.auth.logout')); ?>" method="POST" class="px-3">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-outline-primary w-100 mt-2">Đăng xuất</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>

<?php echo $__env->make('partials.notifications-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/layouts/admin/header.blade.php ENDPATH**/ ?>