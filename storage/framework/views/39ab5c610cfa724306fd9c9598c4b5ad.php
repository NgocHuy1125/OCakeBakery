<script>
document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('form.confirm-delete');
  if (!forms.length) return;

  const showConfirm = (message, onConfirm) => {
    if (window.Swal && typeof window.Swal.fire === 'function') {
      window.Swal.fire({
        title: 'X\u00e1c nh\u1eadn xo\u00e1?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xo\u00e1 ngay',
        cancelButtonText: 'Hu\u1ef7',
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        background: '#fff',
      }).then(result => {
        if (result.isConfirmed) {
          onConfirm();
        }
      });
    } else if (window.confirm(message)) {
      onConfirm();
    }
  };

  forms.forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault();
      const message = form.dataset.confirmMessage || 'B\u1ea1n ch\u1eafc ch\u1eafn mu\u1ed1n xo\u00e1 m\u1ee5c n\u00e0y?';
      showConfirm(message, () => form.submit());
    });
  });
});
</script>
<?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/partials/confirm.blade.php ENDPATH**/ ?>