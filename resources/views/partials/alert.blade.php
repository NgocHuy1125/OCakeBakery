@if(session('success') || session('error') || $errors->any())
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

  @if(session('success'))
    fireToast('{{ session('success') }}', 'success');
  @endif

  @if(session('error'))
    fireToast('{{ session('error') }}', 'error');
  @endif

  @if($errors->any())
    fireToast('Vui lòng kiểm tra lại thông tin.', 'warning');
  @endif
});
</script>
@endif
