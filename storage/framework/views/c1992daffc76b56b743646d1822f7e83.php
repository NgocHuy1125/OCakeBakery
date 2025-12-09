

<?php $__env->startSection('title', 'Tìm kiếm sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $keyword = request('q', request('keyword', ''));
?>

<div class="search-page">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h1 class="h4 mb-0">Kết quả tìm kiếm cho: “<?php echo e($keyword); ?>”</h1>
    <form action="<?php echo e(route('products.search')); ?>" method="GET" class="d-flex" style="max-width:320px">
      <input type="search" name="q" class="form-control me-2" placeholder="Nhập từ khoá..." value="<?php echo e($keyword); ?>">
      <button class="btn btn-success" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
  </div>

  <?php if($products->count()): ?>
    <div class="row g-3">
      <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-6 col-lg-3">
          <?php echo $__env->make('components.product.card', ['product' => $product, 'class' => 'h-100'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="d-flex justify-content-end mt-3">
      <?php echo e($products->onEachSide(1)->links()); ?>

    </div>
  <?php else: ?>
    <div class="alert alert-warning">Không tìm thấy sản phẩm phù hợp với từ khoá “<?php echo e($keyword); ?>”.</div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.search-page .product-card{border:1px solid #e9ecef;border-radius:.75rem;overflow:hidden;background:#fff;transition:transform .15s}
.search-page .product-card:hover{transform:translateY(-2px)}
.search-page .product-img-wrap{aspect-ratio:4/3;overflow:hidden}
.search-page .product-img{width:100%;height:100%;object-fit:cover}
.search-page .old-price{color:#94a3b8;text-decoration:line-through}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.client.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KimLoanCake\resources\views/pages/client/search.blade.php ENDPATH**/ ?>