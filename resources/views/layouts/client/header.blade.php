@php
  $cartCount      = $cartCount      ?? 0;
  $categoriesMenu = $categoriesMenu ?? [];
  $notifications  = $notifications  ?? collect();
  $unreadCount    = $notifications->whereNull('read_at')->count();
@endphp

<style>
  .client-navbar .brand-logo {
    height: 52px;
    width: 52px;
    object-fit: cover;
  }

  .notification-list {
    width: min(380px, 90vw);
    max-height: 350px;
    overflow-y: auto;
  }

  @media (max-width: 575.98px) {
    .client-navbar .brand-logo {
      height: 40px;
      width: 40px;
    }

    .client-navbar .mobile-action-btn {
      padding: 0.35rem 0.55rem;
    }

    .notification-list {
      width: calc(100vw - 24px);
      max-height: 60vh;
    }
  }

  .mobile-account-dropdown .dropdown-menu {
    min-width: 220px;
  }
</style>

<header class="sticky-top">
  <nav class="navbar navbar-expand-lg bg-light border-bottom client-navbar d-none d-md-block">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="brand-logo rounded">
        <span class="text-success fw-bold d-none d-lg-inline">OCAKE BAKERY</span>
      </a>

      <div class="collapse navbar-collapse d-none d-lg-flex" id="topbarDesktop">
        <form class="me-3" role="search" action="{{ url('/search') }}" method="GET" style="min-width:380px;max-width:520px;width:45%;">
          <div class="input-group">
            <input type="search" name="q" class="form-control" placeholder="Nhập tên hoặc loại bánh ưa thích của bạn..." aria-label="Search">
            <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search"></i></button>
          </div>
        </form>

        <ul class="navbar-nav align-items-center ms-auto gap-2">
          <li class="nav-item me-3">
            <a class="btn position-relative" href="{{ url('/cart') }}">
              <i class="fa fa-shopping-cart text-dark"></i>
              <span data-cart-count class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}">{{ $cartCount }}</span>
            </a>
          </li>

          @guest
            <li class="nav-item"><a class="btn btn-success" href="{{ url('/login') }}">Đăng nhập</a></li>
            <li class="nav-item"><a class="btn btn-outline-success" href="{{ url('/register') }}">Đăng ký</a></li>
          @else
            <li class="nav-item dropdown me-2">
              <a class="nav-link text-dark position-relative" href="#" id="notificationDropdown" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span data-notification-count="{{ $unreadCount }}" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadCount > 0 ? '' : 'd-none' }}">{{ $unreadCount }}</span>
              </a>
              @include('partials.notification-list', [
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
                'containerAttrs' => ['aria-labelledby' => 'notificationDropdown'],
                'containerClasses' => 'dropdown-menu dropdown-menu-end p-0 shadow-sm notification-list'
              ])
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-dark" href="#" data-bs-toggle="dropdown">
                <i class="fas fa-user me-1"></i>{{ session('fullname') ?? 'Tài khoản' }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.home') }}"><i class="fas fa-user me-1"></i> Hồ sơ cá nhân</a></li>
                <li><a class="dropdown-item" href="{{ route('profile.orders') }}"><i class="fas fa-clipboard me-1"></i> Đơn hàng</a></li>
                @if(in_array(Auth::user()->role, ['admin', 'staff']))
                  <li><a class="dropdown-item" href="{{ route('admin.offline-orders.index') }}"><i class="fas fa-store me-1"></i> Bán hàng tại quầy</a></li>
                @endif
                @if(Auth::user()->role === 'admin')
                  <li><a class="dropdown-item text-success" href="{{ route('admin.dashboard') }}"><i class="fas fa-user-cog me-1"></i> Quản lý hệ thống</a></li>
                @elseif(Auth::user()->role === 'staff')
                  <li><a class="dropdown-item text-success" href="{{ route('admin.orders.index') }}"><i class="fas fa-clipboard-list me-1"></i> Quản lý đơn hàng</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form action="{{ url('/logout') }}" method="POST" class="px-3 py-1 m-0">@csrf
                    <button class="btn btn-link logout-link p-0 text-danger"><i class="fas fa-sign-out me-1"></i> Đăng xuất</button>
                  </form>
                </li>
              </ul>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container d-flex align-items-center justify-content-between">
      <a class="navbar-brand d-lg-none" href="{{ url('/') }}">
        <img src="{{ asset('/images/logo.png') }}" alt="Logo" style="height:40px;width:40px;object-fit:cover;" class="rounded">
      </a>

      <div class="d-flex d-lg-none align-items-center gap-2 ms-auto">
        <a class="btn position-relative mobile-action-btn" href="{{ url('/cart') }}">
          <i class="fa fa-shopping-cart text-white"></i>
          <span data-cart-count class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}">{{ $cartCount }}</span>
        </a>

        @auth
          <div class="dropdown">
            <button class="btn position-relative mobile-action-btn" type="button" id="notificationDropdownMobile" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-expanded="false">
              <i class="fa fa-bell text-white"></i>
              <span data-notification-count="{{ $unreadCount }}" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unreadCount > 0 ? '' : 'd-none' }}">{{ $unreadCount }}</span>
            </button>
            @include('partials.notification-list', [
              'notifications' => $notifications,
              'unreadCount' => $unreadCount,
              'containerAttrs' => ['aria-labelledby' => 'notificationDropdownMobile'],
              'containerClasses' => 'dropdown-menu dropdown-menu-end p-0 shadow-sm notification-list'
            ])
          </div>

          <div class="dropdown mobile-account-dropdown">
            <button class="btn btn-outline-light mobile-action-btn" type="button" id="accountDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Tài khoản">
              <i class="fa fa-user text-white"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdownMobile">
              <li class="dropdown-header text-muted small">Xin chào, {{ session('fullname') ?? 'bạn' }}</li>
              <li><a class="dropdown-item" href="{{ route('profile.home') }}"><i class="fas fa-user me-1"></i> Hồ sơ cá nhân</a></li>
              <li><a class="dropdown-item" href="{{ route('profile.orders') }}"><i class="fas fa-clipboard me-1"></i> Đơn hàng</a></li>
              @if(in_array(Auth::user()->role, ['admin', 'staff']))
                <li><a class="dropdown-item" href="{{ route('admin.offline-orders.index') }}"><i class="fas fa-store me-1"></i> Bán hàng tại quầy</a></li>
              @endif
              @if(Auth::user()->role === 'admin')
                <li><a class="dropdown-item text-success" href="{{ route('admin.dashboard') }}"><i class="fas fa-user-cog me-1"></i> Quản lý hệ thống</a></li>
              @elseif(Auth::user()->role === 'staff')
                <li><a class="dropdown-item text-success" href="{{ route('admin.orders.index') }}"><i class="fas fa-clipboard-list me-1"></i> Quản lý đơn hàng</a></li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ url('/logout') }}" method="POST" class="px-3 py-1 m-0">@csrf
                  <button class="btn btn-link logout-link p-0 text-danger"><i class="fas fa-sign-out me-1"></i> Đăng xuất</button>
                </form>
              </li>
            </ul>
          </div>
        @endauth

        <button class="btn btn-outline-light mobile-action-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <div class="collapse navbar-collapse d-none d-lg-flex">
        <ul class="navbar-nav mx-auto gap-2">
          <li class="nav-item"><a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Trang chủ</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ url('/products') }}">Sản phẩm</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('promotions*') ? 'active' : '' }}" href="{{ url('/promotions') }}">Khuyến mãi</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('about*') ? 'active' : '' }}" href="{{ url('/about-us') }}">Về chúng tôi</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('store*') ? 'active' : '' }}" href="{{ url('/store') }}">Cửa hàng</a></li>
          <li class="nav-item"><a class="nav-link {{ Request::is('contact*') ? 'active' : '' }}" href="{{ url('/contact') }}">Liên hệ</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
  <div class="offcanvas-header bg-success text-white">
    <h5 class="offcanvas-title d-flex align-items-center gap-2" id="mobileMenuLabel">
      <img src="{{ asset('/images/logo.png') }}" class="rounded" style="height:28px;width:28px;object-fit:cover">
      Tiệm Bánh Kim Loan
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body p-0">
    <div class="p-3 border-bottom">
      <form class="d-flex" action="{{ url('/search') }}" method="GET" role="search">
        <input class="form-control me-2" type="search" name="q" placeholder="Tìm bánh, danh mục, bài viết...">
        <button class="btn btn-success" type="submit">Tìm</button>
      </form>
    </div>

    <ul class="list-group list-group-flush">
      <li class="list-group-item">
        <a class="nav-link d-block text-decoration-none py-2 {{ Request::is('/') ? 'fw-semibold text-success' : '' }}" href="{{ url('/') }}">
          Trang chủ
        </a>
      </li>
      <li class="list-group-item">
        <a class="nav-link d-block text-decoration-none py-2 {{ Request::is('products*') ? 'fw-semibold text-success' : '' }}" href="{{ url('/products') }}">
          Sản phẩm
        </a>
      </li>
      <li class="list-group-item">
        <a class="nav-link d-block text-decoration-none py-2 {{ Request::is('promotions*') ? 'fw-semibold text-success' : '' }}" href="{{ url('/promotions') }}">
          Khuyến mãi
        </a>
      </li>
      <li class="list-group-item">
        <a class="nav-link d-block text-decoration-none py-2 {{ Request::is('about*') ? 'fw-semibold text-success' : '' }}" href="{{ url('/about-us') }}">
          Về chúng tôi
        </a>
      </li>
      <li class="list-group-item">
        <a class="nav-link d-block text-decoration-none py-2 {{ Request::is('store*') ? 'fw-semibold text-success' : '' }}" href="{{ url('/store') }}">
          Cửa hàng
        </a>
      </li>
      <li class="list-group-item">
        <a class="nav-link d-block text-decoration-none py-2 {{ Request::is('contact*') ? 'fw-semibold text-success' : '' }}" href="{{ url('/contact') }}">
          Liên hệ
        </a>
      </li>
      @auth
        <li class="list-group-item">
          <div class="dropdown w-100">
            <button class="btn btn-outline-success w-100 d-flex align-items-center justify-content-between" type="button" id="accountDropdownMobileMenu" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="d-flex align-items-center">
                <i class="fas fa-user me-2"></i>
                {{ session('fullname') ?? 'Tài khoản' }}
              </span>
              <i class="fas fa-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="accountDropdownMobileMenu">
              <li><a class="dropdown-item" href="{{ route('profile.home') }}"><i class="fas fa-user me-1"></i> Hồ sơ cá nhân</a></li>
              <li><a class="dropdown-item" href="{{ route('profile.orders') }}"><i class="fas fa-clipboard me-1"></i> Đơn hàng</a></li>
              @if(in_array(Auth::user()->role, ['admin', 'staff']))
                <li><a class="dropdown-item" href="{{ route('admin.offline-orders.index') }}"><i class="fas fa-store me-1"></i> Bán hàng tại quầy</a></li>
              @endif
              @if(Auth::user()->role === 'admin')
                <li><a class="dropdown-item text-success" href="{{ route('admin.dashboard') }}"><i class="fas fa-user-cog me-1"></i> Quản lý hệ thống</a></li>
              @elseif(Auth::user()->role === 'staff')
                <li><a class="dropdown-item text-success" href="{{ route('admin.orders.index') }}"><i class="fas fa-clipboard-list me-1"></i> Quản lý đơn hàng</a></li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ url('/logout') }}" method="POST" class="px-3 py-1 m-0">@csrf
                  <button class="btn btn-link logout-link p-0 text-danger"><i class="fas fa-sign-out me-1"></i> Đăng xuất</button>
                </form>
              </li>
            </ul>
          </div>
        </li>
      @else
        <li class="list-group-item">
          <a class="btn btn-success w-100" href="{{ url('/login') }}">Đăng nhập</a>
        </li>
        <li class="list-group-item">
          <a class="btn btn-outline-success w-100" href="{{ url('/register') }}">Đăng ký</a>
        </li>
      @endauth
      <li class="list-group-item">
        <a class="btn btn-outline-success w-100 position-relative" href="{{ url('/cart') }}">
          Giỏ hàng
          <span data-cart-count class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $cartCount > 0 ? '' : 'd-none' }}">
            {{ $cartCount }}
          </span>
        </a>
      </li>
    </ul>
  </div>
</div>
