<?php $__env->startSection('title', 'Quản lý sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Danh sách sản phẩm</h4>
  <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-outline-primary">
    <i class="bx bx-plus"></i> Thêm sản phẩm
  </a>
</div>

<?php
  $statusFilters = [
    'draft' => 'Nháp',
    'active' => 'Đang bán',
    'out_of_stock' => 'Hết hàng',
    'archived' => 'Ngừng bán',
  ];
  $perPageOptions = [15, 30, 50, 100];
?>

<form method="GET" class="row g-3 align-items-end mb-3">
  <div class="col-md-4">
    <label class="form-label fw-semibold">Từ khóa</label>
    <input type="text" name="keyword" value="<?php echo e(request('keyword')); ?>" class="form-control" placeholder="Tên hoặc mã sản phẩm">
  </div>
  <div class="col-md-3">
    <label class="form-label fw-semibold">Trạng thái</label>
    <select name="status" class="form-select">
      <option value="">Tất cả</option>
      <?php $__currentLoopData = $statusFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
  <div class="col-md-2 col-sm-6">
    <label class="form-label fw-semibold">Mỗi trang</label>
    <select name="per_page" class="form-select">
      <?php $__currentLoopData = $perPageOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($option); ?>" <?php if((int) request('per_page', $perPage ?? 50) === $option): echo 'selected'; endif; ?>><?php echo e($option); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
  <div class="col-md-3 col-sm-6 d-flex gap-2">
    <button type="submit" class="btn btn-primary flex-grow-1">Lọc</button>
    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary">Đặt lại</a>
  </div>
</form>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Mã sản phẩm</th>
            <th>Sản phẩm</th>
            <th>Danh mục</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Hiển thị</th>
            <th>Trạng thái</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
        </thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td><?php echo e($product->product_code); ?></td>
            <td>
              <div class="d-flex align-items-center">
                <?php
                  $imageUrl = $product->primary_image_url ?: asset('images/product.jpg');
                ?>
                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product->name); ?>" width="60" height="60"
                     class="rounded border object-fit-cover img-fluid me-2" loading="lazy"
                     onerror="this.onerror=null;this.src='<?php echo e(asset('images/logo.png')); ?>';">
                <span><?php echo e($product->name); ?></span>
              </div>
            </td>
            <td><?php echo e($product->primaryCategory->name ?? '—'); ?></td>
            <td>
              <?php if($product->sale_price): ?>
                <div>
                  <span class="fw-semibold text-success"><?php echo e(number_format($product->sale_price, 0, ',', '.')); ?> ₫</span>
                  <small class="text-muted text-decoration-line-through d-block">
                    <?php echo e(number_format($product->listed_price, 0, ',', '.')); ?> ₫
                  </small>
                </div>
              <?php else: ?>
                <span class="fw-semibold"><?php echo e(number_format($product->listed_price, 0, ',', '.')); ?> ₫</span>
              <?php endif; ?>
            </td>
            <td class="text-center"><?php echo e($product->total_stock); ?></td>
            <td class="text-center">
              <?php if($product->show_on_homepage): ?>
                <i class="fas fa-home text-info"></i>
              <?php endif; ?>
              <?php if($product->is_featured): ?>
                <i class="fas fa-crown text-warning ms-1"></i>
              <?php endif; ?>
              <?php if(!$product->show_on_homepage && !$product->is_featured): ?>
                <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
                $statusMap = [
                  'draft' => ['Nháp', 'bg-secondary-subtle text-secondary'],
                  'active' => ['Đang bán', 'bg-success-subtle text-success'],
                  'out_of_stock' => ['Hết hàng', 'bg-warning-subtle text-warning'],
                  'archived' => ['Ngừng bán', 'bg-dark-subtle text-dark'],
                ];
                [$label, $class] = $statusMap[$product->status] ?? [$product->status, 'bg-light text-dark'];
              ?>
              <span class="badge <?php echo e($class); ?>"><?php echo e($label); ?></span>
            </td>
            <td class="text-end">
              <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-edit"></i>
              </a>
              <form action="<?php echo e(route('admin.products.destroy', $product)); ?>" method="POST" class="d-inline confirm-delete">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="9" class="text-center text-muted py-4">
              <i class="bx bx-cube-alt fs-3 mb-1 d-block"></i> Chưa có sản phẩm nào.
            </td>
          </tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php if($products->total()): ?>
    <div class="card-footer bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
      <small class="text-muted">
        Hiển thị <?php echo e($products->firstItem() ?? 0); ?> - <?php echo e($products->lastItem() ?? 0); ?>

        trong tổng số <?php echo e($products->total()); ?> sản phẩm
      </small>
      <?php echo e($products->appends(request()->query())->links('pagination::bootstrap-5')); ?>

    </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/admin/products/index.blade.php ENDPATH**/ ?>