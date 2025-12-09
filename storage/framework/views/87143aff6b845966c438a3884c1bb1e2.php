<?php $__env->startSection('title', 'Quản lý tài khoản'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0 fw-bold">Danh sách tài khoản</h4>
  <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUser">
    <i class="bx bx-plus"></i> Thêm tài khoản
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table id="table" class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th class="text-end pe-4">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><?php echo e($loop->iteration); ?></td>
              <td><?php echo e($user->full_name); ?></td>
              <td><?php echo e($user->email ?? '—'); ?></td>
              <td><?php echo e($user->phone_number ?? '—'); ?></td>
              <td><span class="badge bg-info-subtle text-info text-capitalize"><?php echo e($user->role); ?></span></td>
              <td>
                <?php if($user->status === 'active'): ?>
                  <span class="badge bg-success-subtle text-success">Hoạt động</span>
                <?php elseif($user->status === 'inactive'): ?>
                  <span class="badge bg-secondary-subtle text-secondary">Không hoạt động</span>
                <?php elseif($user->status === 'suspended'): ?>
                  <span class="badge bg-warning-subtle text-warning">Tạm khóa</span>
                <?php else: ?>
                  <span class="badge bg-danger-subtle text-danger">Đã xóa</span>
                <?php endif; ?>
              </td>
              <td class="text-end">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalUserEdit-<?php echo e($user->id); ?>">
                  <i class="fas fa-edit"></i>
                </button>
                <form action="<?php echo e(route('admin.users.destroy', $user->id)); ?>" method="POST" class="d-inline confirm-delete">
                  <?php echo csrf_field(); ?>
                  <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>

            
            <div class="modal fade" id="modalUserEdit-<?php echo e($user->id); ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Cập nhật tài khoản</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form action="<?php echo e(route('admin.users.update', $user->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label">Họ tên</label>
                          <input type="text" name="full_name" value="<?php echo e($user->full_name); ?>" class="form-control" placeholder="Nhập họ tên...">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Email</label>
                          <input type="email" name="email" value="<?php echo e($user->email); ?>" class="form-control" placeholder="Nhập email...">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Số điện thoại</label>
                          <input type="text" name="phone_number" value="<?php echo e($user->phone_number); ?>" class="form-control" placeholder="Nhập số điện thoại...">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Giới tính</label>
                          <select name="gender" class="form-select">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="male" <?php if($user->gender === 'male'): echo 'selected'; endif; ?>>Nam</option>
                            <option value="female" <?php if($user->gender === 'female'): echo 'selected'; endif; ?>>Nữ</option>
                            <option value="other" <?php if($user->gender === 'other'): echo 'selected'; endif; ?>>Khác</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Ngày sinh</label>
                          <input type="date" name="date_of_birth" value="<?php echo e($user->date_of_birth); ?>" class="form-control">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Vai trò</label>
                          <select name="role" class="form-select">
                            <option value="admin" <?php if($user->role === 'admin'): echo 'selected'; endif; ?>>Admin</option>
                            <option value="staff" <?php if($user->role === 'staff'): echo 'selected'; endif; ?>>Nhân viên</option>
                            <option value="customer" <?php if($user->role === 'customer'): echo 'selected'; endif; ?>>Khách hàng</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Trạng thái</label>
                          <select name="status" class="form-select">
                            <option value="active" <?php if($user->status === 'active'): echo 'selected'; endif; ?>>Hoạt động</option>
                            <option value="inactive" <?php if($user->status === 'inactive'): echo 'selected'; endif; ?>>Không hoạt động</option>
                            <option value="suspended" <?php if($user->status === 'suspended'): echo 'selected'; endif; ?>>Tạm khóa</option>
                            <option value="deleted" <?php if($user->status === 'deleted'): echo 'selected'; endif; ?>>Đã xóa</option>
                          </select>
                        </div>
                        <div class="col-12">
                          <label class="form-label">Mật khẩu mới (nếu muốn đổi)</label>
                          <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới...">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                      <button type="submit" class="btn btn-success">Lưu</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                <i class="bx bx-user-circle fs-3 mb-1 d-block"></i>
                Chưa có tài khoản nào.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


<div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thêm tài khoản mới</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?php echo e(route('admin.users.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Họ tên</label>
              <input type="text" name="full_name" class="form-control" placeholder="Nhập họ tên...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Nhập email...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Số điện thoại</label>
              <input type="text" name="phone_number" class="form-control" placeholder="Nhập số điện thoại...">
            </div>
            <div class="col-md-6">
              <label class="form-label">Giới tính</label>
              <select name="gender" class="form-select">
                <option value="">-- Chọn giới tính --</option>
                <option value="male">Nam</option>
                <option value="female">Nữ</option>
                <option value="other">Khác</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Ngày sinh</label>
              <input type="date" name="date_of_birth" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Vai trò</label>
              <select name="role" class="form-select">
                <option value="">-- Chọn vai trò --</option>
                <option value="admin">Admin</option>
                <option value="staff">Nhân viên</option>
                <option value="customer">Khách hàng</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Mật khẩu</label>
              <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu...">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-success">Lưu</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/pages/admin/users.blade.php ENDPATH**/ ?>