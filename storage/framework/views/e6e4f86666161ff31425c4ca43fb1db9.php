<?php $__env->startSection('title', 'Quản lý đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0 fw-bold">Quản lý đơn hàng</h4>
</div>

<?php if($orders->isEmpty()): ?>
  <div class="alert alert-info text-center">Chưa có đơn hàng nào.</div>
<?php else: ?>
  <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $list = $orders->get($status);

        $badgeClass = match($status) {
          'pending' => 'bg-warning text-dark',
          'confirmed' => 'bg-info text-dark',
          'preparing' => 'bg-primary',
          'shipping' => 'bg-secondary',
          'completed' => 'bg-success',
          'cancelled' => 'bg-danger',
          default => 'bg-light text-dark'
        };
      ?>

      <li class="nav-item" role="presentation">
        <button class="nav-link <?php echo e($loop->first ? 'active' : ''); ?>" id="tab-<?php echo e($status); ?>" 
                data-bs-toggle="tab"
                data-bs-target="#content-<?php echo e($status); ?>" 
                type="button" role="tab" aria-controls="content-<?php echo e($status); ?>">
          <?php echo e($label); ?>

          <span class="badge ms-1 <?php echo e($badgeClass); ?>"><?php echo e($list ? $list->count() : 0); ?></span>
        </button>
      </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>

  <div class="tab-content" id="orderTabsContent">
    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php
        $list = $orders->get($status);

        $badgeClass = match($status) {
          'pending' => 'bg-warning text-dark',
          'confirmed' => 'bg-info text-dark',
          'preparing' => 'bg-primary',
          'shipping' => 'bg-secondary',
          'completed' => 'bg-success',
          'cancelled' => 'bg-danger',
          default => 'bg-light text-dark'
        };

        // Payment status badge colors
        $paymentBadgeColors = [
          'pending' => 'bg-warning text-dark',
          'processing' => 'bg-info text-dark',
          'paid' => 'bg-success',
          'failed' => 'bg-danger',
          'refunded' => 'bg-secondary',
        ];
      ?>

      <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" 
           id="content-<?php echo e($status); ?>" role="tabpanel">

        <?php if(!$list): ?>
          <p class="text-muted text-center py-4">Không có đơn hàng nào.</p>
        <?php else: ?>

          <div class="table-responsive">
            <table id="table" class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Mã đơn hàng</th>
                  <th>Khách hàng</th>
                  <th>Tổng tiền</th>
                  <th>Thanh toán</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>

                <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td><?php echo e($loop->iteration); ?></td>

                    <td class="fw-semibold"><?php echo e($order->order_code); ?></td>

                    <td><?php echo e($order->customer_name); ?> • <?php echo e($order->customer_phone); ?></td>

                    <td><?php echo e(number_format($order->grand_total, 0, ',', '.')); ?> ₫</td>

                    
                    <td>
                      <span class="badge <?php echo e($paymentBadgeColors[$order->payment_status] ?? 'bg-light text-dark'); ?>">
                        <?php echo e(ucfirst($order->payment_status)); ?>

                      </span>
                    </td>

                    
                    <td>
                      <span class="badge <?php echo e($badgeClass); ?>"><?php echo e($label); ?></span>
                    </td>

                    <td>
                      
                      <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" 
                        class="btn btn-sm btn-outline-dark me-1">
                        <i class="fas fa-eye"></i>
                      </a>

                      <?php if($order->fulfillment_status === 'pending'): ?>
                        <form method="POST" 
                              action="<?php echo e(route('admin.orders.quickProcess', $order->id)); ?>" 
                              class="d-inline">
                          <?php echo csrf_field(); ?>
                          <?php echo method_field('PUT'); ?>
                          <button class="btn btn-sm btn-outline-success me-1">
                            <i class="fas fa-check"></i>
                          </button>
                        </form>
                      <?php endif; ?>

                      
                      <button class="btn btn-sm btn-outline-primary" 
                              data-bs-toggle="modal"
                              data-bs-target="#modalOrder-<?php echo e($order->id); ?>">
                        <i class="fas fa-edit"></i>
                      </button>
                  </td>

                  </tr>

                  <!-- Modal cập nhật -->
                  <div class="modal fade" id="modalOrder-<?php echo e($order->id); ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">

                        <div class="modal-header">
                          <h5 class="modal-title">Cập nhật đơn: <?php echo e($order->order_code); ?></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form method="POST" action="<?php echo e(route('admin.orders.update', $order->id)); ?>">
                          <?php echo csrf_field(); ?>
                          <?php echo method_field('PUT'); ?>

                          <div class="modal-body">

                            
                            <div class="mb-3">
                              <label class="form-label">Trạng thái đơn hàng</label>
                              <select name="fulfillment_status" class="form-select">
                                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>" 
                                          <?php if($order->fulfillment_status === $key): echo 'selected'; endif; ?>>
                                    <?php echo e($text); ?>

                                  </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>

                            
                            <div class="mb-3">
                              <label class="form-label">Trạng thái thanh toán</label>
                              <select name="payment_status" class="form-select">
                                <?php $__currentLoopData = $paymentBadgeColors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>" 
                                          <?php if($order->payment_status === $key): echo 'selected'; endif; ?>>
                                    <?php echo e(ucfirst($key)); ?>

                                  </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                            </div>

                          </div>

                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" 
                                    data-bs-dismiss="modal">Đóng</button>

                            <button type="submit" class="btn btn-success">Lưu</button>
                          </div>

                        </form>

                      </div>
                    </div>
                  </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

              </tbody>
            </table>
          </div>

        <?php endif; ?>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/admin/orders.blade.php ENDPATH**/ ?>