@props([
    'product',
    'showCategory' => true,
    'showBadge' => true,
    'class' => '',
])

@php
    $price = $product->display_price;
    $originalPrice = $product->original_price;

    $categoryName = $product->primaryCategory->name ?? null;
    $imageUrl = $product->primary_image_url ?: asset('images/product.jpg');
    $fallbackImage = asset('images/logo.png');
@endphp

<div {{ $attributes->merge(['class' => trim('product-card h-100 ' . $class)]) }}>
    <div class="product-img-wrap position-relative">
        <a href="{{ route('products.show', $product->slug) }}" class="d-block">
            <img src="{{ $imageUrl }}"
                 alt="{{ $product->name }}"
                 class="product-img"
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">

            @if($showBadge && $originalPrice && $price < $originalPrice)
                @php
                    $discountPercent = $originalPrice > 0 ? round((1 - ($price / $originalPrice)) * 100) : null;
                @endphp
                @if($discountPercent)
                    <span class="product-badge text-white bg-success fw-semibold">-{{ $discountPercent }}%</span>
                @endif
            @endif
        </a>
    </div>

    <div class="p-3 d-flex flex-column">
        <div class="flex-grow-1">
            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                <h6 class="max-line-2 mb-1">{{ $product->name }}</h6>
            </a>

            @if($showCategory && $categoryName)
                <small class="text-muted d-block mb-2">{{ $categoryName }}</small>
            @endif

            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-baseline gap-2">
                    <span class="price text-success fw-semibold">{{ number_format($price, 0, ',', '.') }}₫</span>
                    @if($originalPrice && $originalPrice > $price)
                        <span class="old-price text-muted text-decoration-line-through">
                            {{ number_format($originalPrice, 0, ',', '.') }}₫
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-grid mt-2">
            <button
                type="button"
                class="btn btn-success btn-add"
                data-add-to-cart
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-quantity="1">
                <i class="fas fa-shopping-cart me-1"></i> Thêm vào giỏ
            </button>
        </div>
    </div>
</div>
