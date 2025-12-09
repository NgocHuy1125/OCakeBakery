<?php $__env->startSection('title', 'Trang tổng quan'); ?>

<?php $__env->startSection('content'); ?>
<style>
  .stat-card {
    border: 0;
    border-radius: 14px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.05);
    transition: 0.25s;
  }
  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
  }
  .stat-icon {
    width: 55px;
    height: 55px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #fff;
  }
  #chart-revenue,
  #chart-orders {
    width: 100%;
    min-height: 360px;
  }
  .dashboard .apexcharts-canvas {
    max-width: 100%;
    overflow: visible !important;
  }
</style>

<div class="dashboard">

  
  <div class="row g-4 mb-4">
    <?php
      $stats = [
        [
          'title' => 'Đơn hàng hôm nay',
          'value' => $todayOrders,
          'icon' => 'ti ti-receipt',
          'bg' => 'linear-gradient(135deg,#6dd5ed,#2193b0)'
        ],
        [
          'title' => 'Doanh thu tháng',
          'value' => number_format($monthRevenue, 0, ',', '.') . ' ₫',
          'icon' => 'ti ti-cash',
          'bg' => 'linear-gradient(135deg,#ff9a9e,#fad0c4)'
        ],
        [
          'title' => 'Sản phẩm đang bán',
          'value' => $activeProducts,
          'icon' => 'ti ti-cookie',
          'bg' => 'linear-gradient(135deg,#a18cd1,#fbc2eb)'
        ],
        [
          'title' => 'Khách hàng mới',
          'value' => $newUsers,
          'icon' => 'ti ti-user',
          'bg' => 'linear-gradient(135deg,#84fab0,#8fd3f4)'
        ],
      ];
    ?>

    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
          <div class="d-flex align-items-center">
            <div class="stat-icon me-3" style="background: <?php echo e($stat['bg']); ?>">
              <i class="<?php echo e($stat['icon']); ?>"></i>
            </div>
            <div>
              <div class="text-muted small"><?php echo e($stat['title']); ?></div>
              <div class="fw-bold fs-5"><?php echo e($stat['value']); ?></div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  
  <div class="row g-4 mb-4">

    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white fw-bold border-0 py-3">
          <i class="ti ti-chart-bar text-success me-1"></i>
          Doanh thu năm <?php echo e(date('Y')); ?>

        </div>
        <div class="card-body">
          <div id="chart-revenue"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white fw-bold border-0 py-3">
          <i class="ti ti-chart-line text-primary me-1"></i>
          Đơn hàng năm <?php echo e(date('Y')); ?>

        </div>
        <div class="card-body">
          <div id="chart-orders"></div>
        </div>
      </div>
    </div>

  </div>

  
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-primary text-white fw-bold border-0 py-3">
      <i class="ti ti-receipt me-1"></i> Đơn hàng gần đây
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Mã đơn</th>
              <th>Khách hàng</th>
              <th>Email</th>
              <th>Tổng tiền</th>
              <th>Thanh toán</th>
              <th>Trạng thái</th>
              <th>Ngày đặt</th>
              <th class="text-center">Hành động</th>
            </tr>
          </thead>

          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                $statusColors = [
                  'pending' => 'warning',
                  'processing' => 'info',
                  'shipped' => 'primary',
                  'delivered' => 'success',
                  'cancelled' => 'danger'
                ];

                $paymentStatusColors = [
                  'pending' => 'warning',
                  'processing' => 'info',
                  'paid' => 'success',
                  'failed' => 'danger',
                  'refunded' => 'secondary',
                ];
              ?>

              <tr>
                <td><?php echo e($index + 1); ?></td>
                <td class="fw-bold"><?php echo e($order->order_code); ?></td>
                <td><?php echo e($order->customer_name); ?></td>
                <td><?php echo e($order->customer_email ?? '—'); ?></td>
                <td><?php echo e(number_format($order->grand_total, 0, ',', '.')); ?> ₫</td>

                
                <td>
                  <span class="badge bg-<?php echo e($paymentStatusColors[$order->payment_status] ?? 'secondary'); ?>">
                    <?php echo e(ucfirst($order->payment_status)); ?>

                  </span>
                </td>

                
                <td>
                  <span class="badge bg-<?php echo e($statusColors[$order->fulfillment_status] ?? 'secondary'); ?>">
                    <?php echo e(ucfirst($order->fulfillment_status)); ?>

                  </span>
                </td>

                <td><?php echo e($order->ordered_at ? \Carbon\Carbon::parse($order->ordered_at)->format('d/m/Y H:i') : '-'); ?></td>

                <td class="text-center">
                  <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-eye"></i>
                  </a>
                </td>
              </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="9" class="text-center text-muted py-3">Không có đơn hàng nào.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  // === Revenue Chart ===
  const chartRevenue = new ApexCharts(document.querySelector("#chart-revenue"), {
    chart: { type: 'bar', height: 330, toolbar: { show: false } },
    series: [{ name: "Doanh thu", data: <?php echo json_encode($monthlyRevenue, 15, 512) ?> }],
    xaxis: { categories: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'] },
    colors: ['#1abc9c'],
    plotOptions: { bar: { borderRadius: 10, columnWidth: '45%' }},
    grid: { borderColor: "#eee" },
    fill: {
      type: "gradient",
      gradient: { shade: "light", type: "vertical", opacityFrom: 0.9, opacityTo: 0.3 }
    },
    tooltip: { y: { formatter: v => v.toLocaleString() + " ₫" } }
  });
  chartRevenue.render();

  // === Orders Chart ===
  const chartOrders = new ApexCharts(document.querySelector("#chart-orders"), {
    chart: { type: 'area', height: 330, toolbar: { show: false }},
    stroke: { curve: 'smooth', width: 3 },
    fill: { type: 'gradient', gradient: { opacityFrom: 0.6, opacityTo: 0.1 }},
    markers: { size: 5 },
    series: [{ name: "Đơn hàng", data: <?php echo json_encode($monthlyOrders, 15, 512) ?> }],
    xaxis: { categories: ['T1','T2','T3','T4','T5','T6','T7','T8','T9','T10','T11','T12'] },
    colors: ['#4e73df'],
    grid: { borderColor: "#eee" },
    tooltip: { y: { formatter: v => v + " đơn" }}
  });
  chartOrders.render();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/admin/dashboard.blade.php ENDPATH**/ ?>