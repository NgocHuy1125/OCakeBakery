<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="/" class="text-nowrap logo-img d-flex align-items-center">
                <img src="<?php echo e(asset('/images/logo.png')); ?>" class="img-fluid" width="60" height="60" alt="Kim Loan Cake logo" />
                <h5 class="fw-bold ms-2 mt-2">Tiệm Bánh<br>Kim Loan</h5>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-6"></i>
            </div>
        </div>
        <hr>
        <?php
            $userRole = auth()->user()->role ?? 'customer';
            $catalogActive = request()->routeIs('admin.categories.*') || request()->routeIs('admin.products.*');
            $inventoryActive = request()->routeIs('admin.restock.*');
        ?>
        <nav class="sidebar-nav scroll-sidebar min-vh-100" data-simplebar="">
            <ul id="sidebarnav">
                <?php if($userRole === 'admin'): ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.dashboard')); ?>" aria-expanded="false">
                            <i class="ti ti-chart-bar"></i>
                            <span class="hide-menu">Trang tổng quan</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link justify-content-between has-arrow <?php echo e($catalogActive ? 'active' : ''); ?>"
                           href="javascript:void(0)" aria-expanded="<?php echo e($catalogActive ? 'true' : 'false'); ?>">
                            <div class="d-flex align-items-center gap-3">
                                <span class="d-flex"><i class="ti ti-clipboard"></i></span>
                                <span class="hide-menu">Quản lý sản phẩm</span>
                            </div>
                        </a>
                        <ul aria-expanded="<?php echo e($catalogActive ? 'true' : 'false'); ?>"
                            class="collapse first-level <?php echo e($catalogActive ? 'show' : ''); ?>">
                            <li class="sidebar-item">
                                <a class="sidebar-link <?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>"
                                   href="<?php echo e(route('admin.categories.index')); ?>">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Danh mục sản phẩm</span>
                                    </div>
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link <?php echo e(request()->routeIs('admin.products.*') ? 'active' : ''); ?>"
                                   href="<?php echo e(route('admin.products.index')); ?>">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Tất cả sản phẩm</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.orders.index')); ?>" aria-expanded="false">
                            <i class="ti ti-receipt"></i>
                            <span class="hide-menu">Quản lý đơn hàng</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e($inventoryActive ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.restock.index')); ?>" aria-expanded="false">
                            <i class="ti ti-truck"></i>
                            <span class="hide-menu">Nhập sản phẩm</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.users.index')); ?>" aria-expanded="false">
                            <i class="ti ti-user"></i>
                            <span class="hide-menu">Quản lý tài khoản</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e(request()->routeIs('admin.promotions.*') ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.promotions.index')); ?>" aria-expanded="false">
                            <i class="ti ti-ticket"></i>
                            <span class="hide-menu">Quản lý khuyến mãi</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e(request()->routeIs('admin.contacts.*') ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.contacts.index')); ?>" aria-expanded="false">
                            <i class="ti ti-mail"></i>
                            <span class="hide-menu">Tin nhắn liên hệ</span>
                        </a>
                    </li>
                <?php elseif($userRole === 'staff'): ?>
                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.orders.index')); ?>" aria-expanded="false">
                            <i class="ti ti-receipt"></i>
                            <span class="hide-menu">Quản lý đơn hàng</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link <?php echo e(request()->routeIs('admin.offline-orders.*') ? 'active' : ''); ?>"
                           href="<?php echo e(route('admin.offline-orders.index')); ?>" aria-expanded="false">
                            <i class="ti ti-building-store"></i>
                            <span class="hide-menu">Bán hàng tại quầy</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <span class="sidebar-divider lg"></span>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<?php /**PATH D:\XAMPP\htdocs\OCakeBakery\resources\views/layouts/admin/sidebar.blade.php ENDPATH**/ ?>