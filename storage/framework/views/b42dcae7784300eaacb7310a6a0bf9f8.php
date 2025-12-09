

<?php $__env->startSection('title', 'Sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div class="products-page">
  <!-- Hero -->
  <section class="hero-banner mb-5 position-relative text-center text-white rounded-4 overflow-hidden hero-bg-bakery">
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="container-fluid hero-content position-relative p-5">
      <h1 class="fw-bold display-5 mb-3 text-uppercase">Bánh ngon mỗi ngày</h1>
      <p class="lead mb-4 col-md-8 col-12 mx-auto">
        Hơn 50 sản phẩm với các loại danh mục khác nhau, toàn bộ đều được đảm bảo chất lượng tốt nhất khi đưa đến tay quý khách hàng
      </p>
      <a href="#products" class="btn btn-light btn-lg px-3">
        Khám phá ngay <i class="fas fa-arrow-up-right-from-square ms-1"></i>
      </a>
    </div>
  </section>

  <!-- Filters + Search -->
  <section class="mb-4" id="products">
    <form class="d-flex flex-column gap-3" method="GET" action="<?php echo e(route('products.index')); ?>">
      <div class="d-flex flex-wrap align-items-center gap-2">
        <?php
          $activeCategory = request('category');
          $queryExceptPage = request()->except(['page']);
          $allParams = $queryExceptPage;
          unset($allParams['category']);
          $allUrl = route('products.index', $allParams);
        ?>
        <a href="<?php echo e($allUrl); ?>"
           class="btn btn-sm rounded-pill <?php echo e(!$activeCategory ? 'btn-success' : 'btn-outline-success cat-pill'); ?>">
          Tất cả
        </a>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $categoryParams = array_merge($queryExceptPage, ['category' => $category->slug]);
            unset($categoryParams['page']);
            $categoryUrl = route('products.index', $categoryParams);
          ?>
          <a href="<?php echo e($categoryUrl); ?>"
             class="btn btn-sm rounded-pill <?php echo e($activeCategory === $category->slug ? 'btn-success' : 'btn-outline-success cat-pill'); ?>">
            <?php echo e($category->name); ?>

          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="input-group" style="max-width: 620px;">
          <span class="input-group-text bg-white"><i class="fa fa-search text-muted"></i></span>
          <input type="search"
                 class="form-control"
                 name="q"
                 value="<?php echo e(request('q')); ?>"
                 placeholder="Tìm theo tên bánh, mã sản phẩm...">
        </div>
      </div>
    </form>
  </section>

  <!-- Product grid -->
  <section class="mb-4">
    <?php if($products->count()): ?>
      <div class="row g-3 g-md-4">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-6 col-md-4 col-lg-3">
            <?php echo $__env->make('components.product.card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <div class="d-flex align-items-center justify-content-between mt-3 flex-wrap gap-2">
        <div class="text-muted small">
          Hiển thị <?php echo e($products->firstItem()); ?> - <?php echo e($products->lastItem()); ?> trong tổng số <?php echo e($products->total()); ?> sản phẩm
        </div>
        <div class="ms-auto">
          <?php echo e($products->onEachSide(1)->links('pagination::bootstrap-5')); ?>

        </div>
      </div>

    <?php else: ?>
      <div class="alert alert-warning">
        Không tìm thấy sản phẩm phù hợp với tiêu chí tìm kiếm.
      </div>
    <?php endif; ?>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/pages/client/products.blade.php ENDPATH**/ ?>