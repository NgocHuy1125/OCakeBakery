<?php $__env->startSection('title', 'Danh mục sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Danh mục sản phẩm</h4>
  <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#createCategoryForm" aria-expanded="false" aria-controls="createCategoryForm">
    <i class="bx bx-plus"></i> Thêm danh mục mới
  </button>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="collapse" id="createCategoryForm">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0 fw-semibold">Tạo danh mục mới</h5>
    </div>
    <div class="card-body">
      <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST" enctype="multipart/form-data" class="vstack gap-3">
        <?php echo csrf_field(); ?>
        <div>
          <label class="form-label fw-semibold">Tên danh mục</label>
          <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục" value="<?php echo e(old('name')); ?>" required>
          <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label class="form-label fw-semibold">Slug</label>
          <input type="text" name="slug" class="form-control" placeholder="Tự sinh nếu để trống, ví dụ: banh-kem-truyen-thong" value="<?php echo e(old('slug')); ?>">
          <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label class="form-label fw-semibold">Ảnh danh mục</label>
          <input type="file" name="image" class="form-control" accept="image/*">
          <small class="text-muted">Định dạng: JPG, PNG, WEBP. Tối đa 2MB.</small>
          <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger d-block"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
          <label class="form-label fw-semibold">Mô tả ngắn</label>
          <textarea name="short_description" class="form-control" rows="3" placeholder="Mô tả ngắn gọn về danh mục..."><?php echo e(old('short_description')); ?></textarea>
          <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Thứ tự hiển thị</label>
            <input type="number" name="display_order" class="form-control" placeholder="0 = mặc định" value="<?php echo e(old('display_order', 0)); ?>">
            <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" value="1" <?php echo e(old('is_visible', true) ? 'checked' : ''); ?>>
              <label class="form-check-label" for="is_visible">Hiển thị</label>
            </div>
          </div>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success mt-3">
            <i class="fas fa-save me-1"></i> Lưu danh mục
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="table" class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Ảnh</th>
            <th>Tên danh mục</th>
            <th>Slug</th>
            <th>Thứ tự</th>
            <th>Hiển thị</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td>
              <?php if($category->image_full_url): ?>
                <img src="<?php echo e($category->image_full_url); ?>" alt="<?php echo e($category->name); ?>" width="60" height="60" class="rounded border object-fit-cover">
              <?php else: ?>
                <img src="<?php echo e(asset('images/no-image.png')); ?>" alt="No Image" width="60" height="60" class="rounded border object-fit-cover opacity-50">
              <?php endif; ?>
            </td>

            <td class="fw-semibold"><?php echo e($category->name); ?></td>
            <td><span class="truncate-250"><?php echo e($category->slug); ?></span></td>
            <td class="text-center"><?php echo e($category->display_order); ?></td>

            <td class="text-center">
              <span class="badge <?php echo e($category->is_visible ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'); ?>">
                <?php echo e($category->is_visible ? 'Hiển thị' : 'Ẩn'); ?>

              </span>
            </td>

            <td class="text-end pe-4">
              <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCategory<?php echo e($category->id); ?>">
                <i class="fas fa-edit"></i>
              </button>
              <form action="<?php echo e(route('admin.categories.destroy', $category)); ?>" method="POST" class="d-inline confirm-delete">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-outline-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>

          <!-- Modal chỉnh sửa -->
          <?php $__env->startPush('modals'); ?>
          <div class="modal fade" id="editCategory<?php echo e($category->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title fw-semibold">Chỉnh sửa danh mục</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <form action="<?php echo e(route('admin.categories.update', $category)); ?>" method="POST" enctype="multipart/form-data">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('PUT'); ?>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label">Tên danh mục</label>
                      <input type="text" name="name" class="form-control" value="<?php echo e($category->name); ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Slug</label>
                      <input type="text" name="slug" class="form-control" value="<?php echo e($category->slug); ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Ảnh danh mục</label><br>
                      <?php if($category->image_full_url): ?>
                        <img src="<?php echo e($category->image_full_url); ?>" width="80" class="rounded border mb-2">
                      <?php endif; ?>
                      <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Mô tả ngắn</label>
                      <textarea name="short_description" class="form-control" rows="3"><?php echo e($category->short_description); ?></textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Thứ tự hiển thị</label>
                      <input type="number" name="display_order" class="form-control" value="<?php echo e($category->display_order); ?>">
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="is_visible" id="edit-visible-<?php echo e($category->id); ?>" value="1" <?php echo e($category->is_visible ? 'checked' : ''); ?>>
                      <label for="edit-visible-<?php echo e($category->id); ?>" class="form-check-label">Hiển thị</label>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Lưu</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <?php $__env->stopPush(); ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="bx bx-category fs-3 mb-1 d-block"></i> Chưa có danh mục nào.
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/admin/categories/index.blade.php ENDPATH**/ ?>