<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin | Tiệm Bánh Kim Loan'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('/images/logo.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/libs/bootstrap/dist/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/icons/tabler-icons/tabler-icons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/styles.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/main.css')); ?>">
    <script src="<?php echo e(asset('admin/assets/js/customize.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/fontawesome/css/all.min.css')); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/alert.css')); ?>">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="<?php echo e(asset('admin/assets/js/customize.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/global.css')); ?>">
</head>
<body>
<div class="page-wrapper" id="main-wrapper" data-layout="vertical"
     data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed">

    <?php echo $__env->make('layouts.admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="body-wrapper pt-wrapper">
        <?php echo $__env->make('layouts.admin.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="container-fluid py-4">
            <?php echo $__env->make('partials.alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->make('partials.confirm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>

        <?php echo $__env->make('layouts.admin.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<script src="<?php echo e(asset('admin/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/libs/simplebar/dist/simplebar.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/sidebarmenu.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/app.min.js')); ?>"></script>
<script>
    document.querySelectorAll('.sidebartoggler').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelector('.left-sidebar').classList.toggle('active');
        });
    });
</script>
<div class="toast-container position-fixed top-0 end-0 p-3" id="adminToastContainer" style="z-index: 1090;"></div>
<script>
    (() => {
        const container = document.getElementById('adminToastContainer');
        window.adminToast = function ({ type = 'success', title = '', message = '', autohide = true, delay = 5000 } = {}) {
            if (!container) {
                alert(message || title || 'Đã xử lý xong.');
                return;
            }

            const toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : type);
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${title ? `<strong class="d-block mb-1">${title}</strong>` : ''}
                        ${message || ''}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;

            container.appendChild(toastEl);
            const toast = new bootstrap.Toast(toastEl, { autohide, delay });
            toast.show();
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        };
    })();
</script>
<?php echo $__env->yieldPushContent('modals'); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/layouts/admin/master.blade.php ENDPATH**/ ?>