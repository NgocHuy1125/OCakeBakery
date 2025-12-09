<?php
  $notifications = $notifications ?? collect();
  $unreadCount = $unreadCount ?? 0;
  $containerTag = ($asList ?? true) ? 'ul' : 'div';
  $itemTag = ($asList ?? true) ? 'li' : 'div';
  $containerClasses = $containerClasses ?? 'dropdown-menu dropdown-menu-end p-0';
  $containerAttrs = $containerAttrs ?? [];
  $style = $style ?? 'max-height: 350px; overflow-y: auto;';
?>

<<?php echo e($containerTag); ?>

  class="<?php echo e($containerClasses); ?>"
  style="<?php echo e($style); ?>"
  <?php $__currentLoopData = $containerAttrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attrName => $attrValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo e($attrName); ?>="<?php echo e($attrValue); ?>"
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
>
  <<?php echo e($itemTag); ?> class="dropdown-header bg-light fw-bold text-center py-2 d-flex justify-content-between align-items-center px-3">
    <span>Thông báo</span>
    <?php if($unreadCount > 0): ?>
      <button class="btn btn-sm btn-outline-secondary mark-all-btn" data-mark-all-url="<?php echo e(route('notifications.markAll')); ?>">Đánh dấu tất cả</button>
    <?php endif; ?>
  </<?php echo e($itemTag); ?>>

  <?php if($notifications->count() > 0): ?>
    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notify): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <<?php echo e($itemTag); ?>>
        <a href="<?php echo e($notify->link ?? '#'); ?>"
          class="dropdown-item small py-2 border-bottom d-flex align-items-start notification-item"
          data-mark-url="<?php echo e(route('notifications.markRead', $notify->id)); ?>">
          <i class="fas fa-circle notification-dot text-<?php echo e($notify->read_at ? 'secondary' : 'primary'); ?> me-2 mt-1" style="font-size:10px;"></i>
          <div>
            <div class="fw-semibold"><?php echo e($notify->title); ?></div>
            <div class="text-muted small"><?php echo e($notify->created_at?->diffForHumans()); ?></div>
          </div>
        </a>
      </<?php echo e($itemTag); ?>>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php else: ?>
    <<?php echo e($itemTag); ?> class="dropdown-item text-center text-muted py-3">Chưa có thông báo nào</<?php echo e($itemTag); ?>>
  <?php endif; ?>
</<?php echo e($containerTag); ?>>
<?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/partials/notification-list.blade.php ENDPATH**/ ?>