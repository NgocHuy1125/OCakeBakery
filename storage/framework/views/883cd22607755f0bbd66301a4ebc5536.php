<?php if(session('success') || session('error') || $errors->any()): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const fireToast = (message, variant) => {
    const type = variant === 'error' ? 'error' : (variant === 'warning' ? 'warning' : 'success');

    if (typeof window.flashToast === 'function') {
      window.flashToast({
        type,
        title: variant === 'error' ? 'Lỗi' : (variant === 'warning' ? 'Thông báo' : 'Thành công'),
        message,
      });
      return;
    }

    if (typeof Swal === 'undefined') return;

    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      showCloseButton: true,
      timer: 5000,
      timerProgressBar: true,
      background: '#333',
      color: '#fff',
      didClose: () => {
        const el = document.querySelector('[data-swal-toast]');
        if (el) el.remove();
      }
    });

    Toast.fire({
      icon: type,
      title: message
    });
  };

  <?php if(session('success')): ?>
    fireToast('<?php echo e(session('success')); ?>', 'success');
  <?php endif; ?>

  <?php if(session('error')): ?>
    fireToast('<?php echo e(session('error')); ?>', 'error');
  <?php endif; ?>

  <?php if($errors->any()): ?>
    fireToast('Vui lòng kiểm tra lại thông tin.', 'warning');
  <?php endif; ?>
});
</script>
<?php endif; ?>
<?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/partials/alert.blade.php ENDPATH**/ ?>