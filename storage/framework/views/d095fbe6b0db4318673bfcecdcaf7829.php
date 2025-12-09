<?php $__env->startSection('title', 'Chi tiết đơn ' . $order->order_code); ?>

<?php $__env->startSection('content'); ?>
<?php
    $paymentLabels = [
        'pending' => 'Chờ thanh toán',
        'processing' => 'Đang xử lý',
        'paid' => 'Đã thanh toán',
        'failed' => 'Thanh toán thất bại',
        'refunded' => 'Đã hoàn tiền',
    ];

    $paymentBadgeClasses = [
        'pending' => 'bg-warning text-dark',
        'processing' => 'bg-info text-dark',
        'paid' => 'bg-success',
        'failed' => 'bg-danger',
        'refunded' => 'bg-secondary',
    ];

    $fulfillmentLabels = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã hủy',
    ];

    $fulfillmentBadgeClasses = [
        'pending' => 'bg-secondary',
        'processing' => 'bg-info text-dark',
        'shipped' => 'bg-warning text-dark',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-dark',
    ];

    $sourceLabels = [
        'website' => 'Website',
        'facebook' => 'Facebook',
        'zalo' => 'Zalo',
        'store' => 'Tại cửa hàng',
    ];

    $orderedAt = optional($order->ordered_at)->format('d/m/Y H:i');
    $statusHistory = $order->statusHistory->sortBy('created_at');
    $latestTransaction = $order->transactions->first();
?>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>" class="text-success text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('profile.orders')); ?>" class="text-success text-decoration-none">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đơn #<?php echo e($order->order_code); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h4 class="fw-bold text-success mb-1">Đơn hàng #<?php echo e($order->order_code); ?></h4>
                            <div class="text-muted">Đặt lúc <?php echo e($orderedAt ?? '---'); ?></div>
                            <div class="text-muted">Nguồn: <?php echo e($sourceLabels[$order->source_channel] ?? ucfirst($order->source_channel)); ?></div>
                        </div>
                        <div class="text-md-end">
                            <span class="badge <?php echo e($paymentBadgeClasses[$order->payment_status] ?? 'bg-secondary'); ?> me-2">
                                <?php echo e($paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status)); ?>

                            </span>
                            <span class="badge <?php echo e($fulfillmentBadgeClasses[$order->fulfillment_status] ?? 'bg-success'); ?>">
                                <?php echo e($fulfillmentLabels[$order->fulfillment_status] ?? ucfirst($order->fulfillment_status)); ?>

                            </span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-uppercase text-muted mb-3">Thông tin giao hàng</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2"><strong>Người nhận:</strong> <?php echo e($order->customer_name); ?></li>
                                <li class="mb-2"><strong>Số điện thoại:</strong> <?php echo e($order->customer_phone); ?></li>
                                <li class="mb-2"><strong>Email:</strong> <?php echo e($order->customer_email ?? '---'); ?></li>
                                <li class="mb-2">
                                    <strong>Địa chỉ:</strong> <?php echo e($order->address_line); ?>, <?php echo e($order->ward_name); ?>, <?php echo e($order->district_name); ?>

                                </li>
                                <li class="mb-2"><strong>Ghi chú của khách:</strong> <?php echo e($order->customer_note ?? 'Không có'); ?></li>
                                <?php if($order->internal_note): ?>
                                    <li class="mb-2"><strong>Ghi chú nội bộ:</strong> <?php echo e($order->internal_note); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold text-uppercase text-muted mb-3">Thanh toán & Giao hàng</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2"><strong>Phương thức:</strong> <?php echo e($order->payment_method_label); ?></li>
                                <li class="mb-2"><strong>Đơn vị xử lý:</strong> <?php echo e($order->payment_provider_label); ?></li>
                                <li class="mb-2"><strong>Trạng thái thanh toán:</strong> <?php echo e($paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status)); ?></li>
                                <li class="mb-2"><strong>Trạng thái giao hàng:</strong> <?php echo e($fulfillmentLabels[$order->fulfillment_status] ?? ucfirst($order->fulfillment_status)); ?></li>
                                <li class="mb-2"><strong>Tiền cọc:</strong> <?php echo e($order->deposit_amount ? number_format($order->deposit_amount, 0, ',', '.') . ' đ' : 'Không'); ?></li>
                                <li class="mb-2"><strong>Mã khuyến mãi:</strong> <?php echo e($order->coupon?->coupon_code ?? 'Không áp dụng'); ?></li>
                                <?php if($latestTransaction): ?>
                                    <li>
                                        <strong>Giao dịch mới nhất:</strong>
                                        <div class="text-muted">
                                            <?php echo e($latestTransaction->transaction_code ?? '---'); ?> · <?php echo e(number_format($latestTransaction->amount, 0, ',', '.')); ?> đ
                                        </div>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold text-uppercase text-muted">Danh sách sản phẩm</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Sản phẩm</th>
                                    <th style="width: 12%" class="text-center">SL</th>
                                    <th style="width: 18%" class="text-end">Đơn giá</th>
                                    <th style="width: 20%" class="text-end pe-4">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-semibold"><?php echo e($item->product_name_snapshot); ?></div>
                                            <div class="text-muted small"><?php echo e($item->variant_name_snapshot); ?></div>
                                        </td>
                                        <td class="text-center"><?php echo e($item->quantity); ?></td>
                                        <td class="text-end"><?php echo e(number_format($item->sale_price ?? $item->list_price, 0, ',', '.')); ?> đ</td>
                                        <td class="text-end pe-4"><?php echo e(number_format($item->line_total, 0, ',', '.')); ?> đ</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td class="ps-4" colspan="3">Tạm tính</td>
                                    <td class="text-end pe-4"><?php echo e(number_format($order->subtotal_amount, 0, ',', '.')); ?> đ</td>
                                </tr>
                                <tr>
                                    <td class="ps-4" colspan="3">Giảm giá</td>
                                    <td class="text-end pe-4 text-danger">-<?php echo e(number_format($order->discount_amount, 0, ',', '.')); ?> đ</td>
                                </tr>
                                <tr>
                                    <td class="ps-4" colspan="3">Phí vận chuyển</td>
                                    <td class="text-end pe-4"><?php echo e(number_format($order->shipping_fee, 0, ',', '.')); ?> đ</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td class="ps-4" colspan="3">Tổng cộng</td>
                                    <td class="text-end pe-4 text-success"><?php echo e(number_format($order->grand_total, 0, ',', '.')); ?> đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-semibold text-uppercase text-muted">Nhật ký trạng thái</h6>
                </div>
                <div class="card-body">
                    <?php if($statusHistory->isEmpty()): ?>
                        <p class="text-muted small mb-0">Đơn hàng chưa có cập nhật nào ngoài trạng thái hiện tại.</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-0">
                            <?php $__currentLoopData = $statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mb-3">
                                    <div class="fw-semibold">
                                        <?php if($history->status_type === 'payment'): ?>
                                            <?php echo e($paymentLabels[$history->status_value] ?? ucfirst($history->status_value)); ?>

                                        <?php else: ?>
                                            <?php echo e($fulfillmentLabels[$history->status_value] ?? ucfirst($history->status_value)); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div class="text-muted small">
                                        <?php echo e(optional($history->created_at)->format('d/m/Y H:i')); ?>

                                        <?php if($history->note): ?>
                                            · <?php echo e($history->note); ?>

                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body d-grid gap-2">
                    <a href="<?php echo e(route('products.index')); ?>" class="btn btn-success fw-semibold">
                        <i class="fas fa-shopping-bag me-1"></i> Tiếp tục mua sắm
                    </a>
                    <a href="<?php echo e(route('profile.orders')); ?>" class="btn btn-outline-success fw-semibold">
                        <i class="fas fa-receipt me-1"></i> Xem danh sách đơn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/client/detail/orders.blade.php ENDPATH**/ ?>