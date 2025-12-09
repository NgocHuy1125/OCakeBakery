<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tiệm bánh Kim Loan - Trang Chủ</title>
  <link rel="icon" type="image/png" href="{{ asset('/images/logo.png') }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts: Lexend -->
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome 6 (CDN) -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/alert.css') }}">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/pagination.css') }}">

  @stack('styles')
  <style>
    #appToastContainer {
      top: 5.25rem;
    }

    @media (max-width: 767.98px) {
      #appToastContainer {
        top: 4.5rem;
      }
    }
  </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

  @include('layouts.client.header')

  <main class="py-4">
    <div class="container">
      @include('partials.alert')
      @include('partials.confirm')

      @yield('content')
    </div>
  </main>

  <div class="toast-container position-fixed end-0 p-3" id="appToastContainer" style="z-index: 1080;"></div>

  @include('layouts.client.footer')

  @php
    $manifestPath = public_path('build/manifest.json');
    $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : [];
    $appJs = $manifest['resources/js/app.js']['file'] ?? '';
  @endphp

  <script>
    window.routes = {
      cartAdd: "{{ route('cart.add') }}",
    };
    window.csrfToken = "{{ csrf_token() }}";
  </script>

  @if ($appJs)
      <script type="module" src="{{ asset('build/' . $appJs) }}"></script>
  @endif

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (() => {
      const container = document.getElementById('appToastContainer');

      window.flashToast = function ({ type = 'success', title = '', message = '', autohide = true, delay = 5000 } = {}) {
        if (!container) {
          alert(message || title || 'Đã xử lý xong.');
          return;
        }

        const toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-bg-' + (type === 'error' ? 'danger' : type);
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');

        toastEl.innerHTML = `
          <div class="d-flex">
            <div class="toast-body">
              ${title ? `<strong class="d-block mb-1">${title}</strong>` : ''}
              ${message || ''}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        `;

        container.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { autohide, delay });
        toast.show();

        toastEl.addEventListener('hidden.bs.toast', () => {
          toastEl.remove();
        });
      };
    })();

    // Fallback add-to-cart handler (đảm bảo hoạt động kể cả khi bundle JS chưa load)
    (() => {
      const cartAddUrl = window.routes?.cartAdd;
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
      if (!cartAddUrl || !csrfToken) return;

      const showToast = (payload = {}) => {
        if (typeof window.flashToast === 'function') {
          window.flashToast({ ...payload, autohide: true, delay: 5000 });
          return;
        }
        alert(payload.message || payload.title || 'Đã xử lý xong.');
      };

      const updateCartCount = (count) => {
        document.querySelectorAll('[data-cart-count]').forEach((el) => {
          el.textContent = count;
          el.classList.toggle('d-none', parseInt(count, 10) <= 0);
        });
      };

      document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-add-to-cart]');
        if (!button) return;

        if (window.__cartAddHandlerRegistered) return;

        event.preventDefault();
        if (!cartAddUrl) return;

        const productId = button.dataset.productId;
        const quantity = parseInt(button.dataset.quantity || '1', 10);

        if (!productId) {
          showToast({ type: 'error', title: 'Thiếu sản phẩm', message: 'Không xác định được sản phẩm.' });
          return;
        }

        button.classList.add('disabled');

        try {
          const res = await fetch(cartAddUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              Accept: 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity }),
          });

          const data = await res.json().catch(() => ({}));

          if (data.toast) showToast(data.toast);

          if (!res.ok || !data.ok) return;

          updateCartCount(data.cart_count ?? 0);
        } catch (error) {
          console.error(error);
          showToast({ type: 'error', title: 'Lỗi giỏ hàng', message: 'Không thể thêm vào giỏ, vui lòng thử lại.' });
        } finally {
          button.classList.remove('disabled');
        }
      });
    })();
  </script>

  @include('partials.notifications-script')
  @stack('scripts')
</body>
</html>
