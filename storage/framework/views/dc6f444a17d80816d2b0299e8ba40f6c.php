<?php $__env->startSection('title', 'Tin nhắn liên hệ'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold mb-0">Tin nhắn liên hệ</h4>
</div>

<?php if($contacts->isEmpty()): ?>
  <div class="alert alert-info mb-0 shadow-sm">
    <i class="fas fa-inbox me-2"></i>Hiện chưa có tin nhắn nào được gửi.
  </div>
<?php else: ?>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="table" class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 50px;">#</th>
              <th>Họ tên</th>
              <th>Email</th>
              <th>Số điện thoại</th>
              <th>Nội dung</th>
              <th>Trạng thái</th>
              <th>Ngày gửi</th>
              <th class="text-center" style="width: 120px;">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $statusLabels = [
                'pending' => ['text' => 'Chờ xử lý', 'class' => 'bg-warning text-dark'],
                'answered' => ['text' => 'Đã phản hồi', 'class' => 'bg-success'],
                'closed' => ['text' => 'Đã đóng', 'class' => 'bg-secondary'],
              ];
              $status = $statusLabels[$contact->status] ?? ['text' => ucfirst($contact->status), 'class' => 'bg-secondary'];
            ?>
            <tr class="<?php echo e($contact->status === 'pending' ? 'fw-semibold bg-light' : ''); ?>">
              <td><?php echo e($loop->iteration); ?></td>
              <td><?php echo e($contact->full_name); ?></td>
              <td><?php echo e($contact->email); ?></td>
              <td><?php echo e($contact->phone_number ?? '—'); ?></td>

              <td>
                <a href="#" 
                   class="text-decoration-none text-muted small show-message" 
                   data-bs-toggle="modal" 
                   data-bs-target="#messageModal" 
                   data-name="<?php echo e($contact->full_name); ?>"
                   data-email="<?php echo e($contact->email); ?>"
                   data-phone="<?php echo e($contact->phone_number ?? '—'); ?>"
                   data-date="<?php echo e($contact->created_at->format('d/m/Y H:i')); ?>"
                   data-message="<?php echo e($contact->message); ?>">
                   <?php echo e(Str::limit($contact->message, 50, '...')); ?>

                </a>
              </td>

              <td>
                <span class="badge <?php echo e($status['class']); ?>"><?php echo e($status['text']); ?></span>
              </td>
              <td><?php echo e($contact->created_at->format('d/m/Y H:i')); ?></td>
              <td class="text-center">
                <?php if($contact->status === 'pending'): ?>
                  <form action="<?php echo e(route('admin.contacts.markAsRead', $contact)); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button class="btn btn-sm btn-outline-info" title="Đánh dấu đã xem">
                      <i class="fas fa-eye"></i>
                    </button>
                  </form>
                <?php endif; ?>
                <form action="<?php echo e(route('admin.contacts.destroy', $contact)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Xóa tin nhắn này?')">
                  <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                  <button class="btn btn-sm btn-outline-danger" title="Xóa tin nhắn">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Modal hiển thị chi tiết -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title" id="messageModalLabel"><i class="fas fa-envelope me-2"></i>Chi tiết tin nhắn</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p><strong>Họ tên:</strong> <span id="modalName"></span></p>
        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
        <p><strong>Số điện thoại:</strong> <span id="modalPhone"></span></p>
        <p><strong>Gửi lúc:</strong> <span id="modalDate"></span></p>
        <hr>
        <p class="mb-0"><strong>Nội dung:</strong></p>
        <p id="modalMessage" class="mt-2 text-muted"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-xmark me-1"></i>Đóng</button>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show-message').forEach(link => {
      link.addEventListener('click', function () {
        document.getElementById('modalName').textContent = this.dataset.name;
        document.getElementById('modalEmail').textContent = this.dataset.email;
        document.getElementById('modalPhone').textContent = this.dataset.phone;
        document.getElementById('modalDate').textContent = this.dataset.date;
        document.getElementById('modalMessage').textContent = this.dataset.message;
      });
    });
  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/pages/admin/contact.blade.php ENDPATH**/ ?>