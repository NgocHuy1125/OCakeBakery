<?php $__env->startSection('title', 'Chỉnh sửa sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Chỉnh sửa sản phẩm</h4>
  <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-dark">
    <i class="fas fa-angle-left me-1"></i> Quay lại
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form action="<?php echo e(route('admin.products.update', $product)); ?>"
          method="POST" enctype="multipart/form-data" class="row g-4">
      <?php echo csrf_field(); ?>
      <?php echo method_field('PUT'); ?>

      <div class="col-lg-8">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label fw-semibold">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control"
                   value="<?php echo e(old('name', $product->name)); ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Mã sản phẩm</label>
            <input type="text" name="product_code" class="form-control"
                   value="<?php echo e(old('product_code', $product->product_code)); ?>" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Slug</label>
            <input type="text" name="slug" class="form-control"
                   value="<?php echo e(old('slug', $product->slug)); ?>" placeholder="Tự sinh nếu để trống">
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Mô tả ngắn</label>
            <textarea name="short_description" class="form-control" rows="2"><?php echo e(old('short_description', $product->short_description)); ?></textarea>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="5"><?php echo e(old('description', $product->description)); ?></textarea>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">Ảnh sản phẩm</label>
            <input type="file" name="image_files[]" multiple accept="image/*" class="form-control" id="imageUpload">
            <div id="previewImages" class="d-flex flex-wrap gap-2 mt-3">
              <?php $__empty_1 = true; $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <img src="<?php echo e($image->resolved_url ?? asset('images/product.jpg')); ?>" width="100" height="100"
                     class="rounded border object-fit-cover current-image">
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <img src="<?php echo e($product->primary_image_url ?? asset('images/product.jpg')); ?>" width="100" height="100"
                     class="rounded border object-fit-cover current-image">
              <?php endif; ?>
            </div>
            <small class="text-muted">Chọn ảnh mới nếu bạn muốn thay thế bộ ảnh hiện tại.</small>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="vstack gap-3">
          <div>
            <label class="form-label fw-semibold">Danh mục chính</label>
            <select name="primary_category_id" class="form-select" required>
              <option value="">Chọn danh mục</option>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>"
                        <?php if(old('primary_category_id', $product->primary_category_id) == $category->id): echo 'selected'; endif; ?>>
                  <?php echo e($category->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>

          <div class="row g-3">
            <div class="col-6">
              <label class="form-label fw-semibold">Giá niêm yết</label>
              <input type="number" step="0.01" name="listed_price" class="form-control"
                     value="<?php echo e(old('listed_price', $product->listed_price)); ?>" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Giá khuyến mãi</label>
              <input type="number" step="0.01" name="sale_price" class="form-control"
                     value="<?php echo e(old('sale_price', $product->sale_price)); ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Tồn kho</label>
              <input type="number" name="total_stock" class="form-control"
                     value="<?php echo e(old('total_stock', $product->total_stock)); ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Đơn vị tính</label>
              <input type="text" name="unit_name" class="form-control"
                     value="<?php echo e(old('unit_name', $product->unit_name ?? 'sản phẩm')); ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Trạng thái</label>
              <select name="status" class="form-select">
                <option value="draft" <?php if(old('status', $product->status) == 'draft'): echo 'selected'; endif; ?>>Nháp</option>
                <option value="active" <?php if(old('status', $product->status) == 'active'): echo 'selected'; endif; ?>>Đang bán</option>
                <option value="out_of_stock" <?php if(old('status', $product->status) == 'out_of_stock'): echo 'selected'; endif; ?>>Hết hàng</option>
                <option value="archived" <?php if(old('status', $product->status) == 'archived'): echo 'selected'; endif; ?>>Ngừng bán</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Tên biến thể</label>
              <input type="text" name="variant_name" class="form-control"
                     value="<?php echo e(old('variant_name', $variant->variant_name ?? 'Mặc định')); ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">SKU</label>
              <input type="text" name="sku" class="form-control"
                     value="<?php echo e(old('sku', $variant->sku ?? '')); ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Giá biến thể</label>
              <input type="number" step="0.01" name="variant_price" class="form-control"
                     value="<?php echo e(old('variant_price', $variant->price ?? $product->listed_price)); ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">KM biến thể</label>
              <input type="number" step="0.01" name="variant_sale_price" class="form-control"
                     value="<?php echo e(old('variant_sale_price', $variant->sale_price ?? $product->sale_price)); ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Tồn kho biến thể</label>
              <input type="number" name="variant_stock_quantity" class="form-control"
                     value="<?php echo e(old('variant_stock_quantity', $variant->stock_quantity ?? $product->total_stock ?? 0)); ?>">
            </div>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="show_on_homepage"
                   id="show_on_homepage" value="1"
                   <?php if(old('show_on_homepage', $product->show_on_homepage)): echo 'checked'; endif; ?>>
            <label for="show_on_homepage" class="form-check-label">Hiển thị trang chủ</label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured"
                   id="is_featured" value="1"
                   <?php if(old('is_featured', $product->is_featured)): echo 'checked'; endif; ?>>
            <label for="is_featured" class="form-check-label">Sản phẩm nổi bật</label>
          </div>

          <button type="submit" class="btn btn-success w-100">Cập nhật sản phẩm</button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('imageUpload')?.addEventListener('change', function (e) {
  const preview = document.getElementById('previewImages');
  preview.innerHTML = '';

  Array.from(e.target.files).forEach((file) => {
    if (!file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = (event) => {
      const img = document.createElement('img');
      img.src = event.target.result;
      img.className = 'rounded border object-fit-cover';
      img.style = 'width:100px;height:100px;margin-right:6px';
      preview.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/admin/products/edit.blade.php ENDPATH**/ ?>