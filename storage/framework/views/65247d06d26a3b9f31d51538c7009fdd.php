<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'product',
    'showCategory' => true,
    'showBadge' => true,
    'class' => '',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'product',
    'showCategory' => true,
    'showBadge' => true,
    'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $price = $product->display_price;
    $originalPrice = $product->original_price;

    $categoryName = $product->primaryCategory->name ?? null;
    $imageUrl = $product->primary_image_url ?: asset('images/product.jpg');
    $fallbackImage = asset('images/logo.png');
?>

<div <?php echo e($attributes->merge(['class' => trim('product-card h-100 ' . $class)])); ?>>
    <div class="product-img-wrap position-relative">
        <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="d-block">
            <img src="<?php echo e($imageUrl); ?>"
                 alt="<?php echo e($product->name); ?>"
                 class="product-img"
                 loading="lazy"
                 onerror="this.onerror=null;this.src='<?php echo e($fallbackImage); ?>';">

            <?php if($showBadge && $originalPrice && $price < $originalPrice): ?>
                <?php
                    $discountPercent = $originalPrice > 0 ? round((1 - ($price / $originalPrice)) * 100) : null;
                ?>
                <?php if($discountPercent): ?>
                    <span class="product-badge text-white bg-success fw-semibold">-<?php echo e($discountPercent); ?>%</span>
                <?php endif; ?>
            <?php endif; ?>
        </a>
    </div>

    <div class="p-3 d-flex flex-column">
        <div class="flex-grow-1">
            <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="text-decoration-none text-dark">
                <h6 class="max-line-2 mb-1"><?php echo e($product->name); ?></h6>
            </a>

            <?php if($showCategory && $categoryName): ?>
                <small class="text-muted d-block mb-2"><?php echo e($categoryName); ?></small>
            <?php endif; ?>

            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-baseline gap-2">
                    <span class="price text-success fw-semibold"><?php echo e(number_format($price, 0, ',', '.')); ?>₫</span>
                    <?php if($originalPrice && $originalPrice > $price): ?>
                        <span class="old-price text-muted text-decoration-line-through">
                            <?php echo e(number_format($originalPrice, 0, ',', '.')); ?>₫
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="d-grid mt-2">
            <button
                type="button"
                class="btn btn-success btn-add"
                data-add-to-cart
                data-product-id="<?php echo e($product->id); ?>"
                data-product-name="<?php echo e($product->name); ?>"
                data-quantity="1">
                <i class="fas fa-shopping-cart me-1"></i> Thêm vào giỏ
            </button>
        </div>
    </div>
</div>
<?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/components/product/card.blade.php ENDPATH**/ ?>