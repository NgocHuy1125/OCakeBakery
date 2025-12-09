@php
    use Illuminate\Support\Str;
@endphp

@php
    $recentOrders = $recentOrders ?? collect();
    $posFulfillmentStatuses = [
        'pending' => ['label' => 'Chưa xử lý', 'class' => 'bg-warning text-dark'],
        'processing' => ['label' => 'Đang xử lý', 'class' => 'bg-info text-dark'],
        'shipped' => ['label' => 'Đang giao', 'class' => 'bg-primary'],
        'delivered' => ['label' => 'Đã giao', 'class' => 'bg-success'],
        'cancelled' => ['label' => 'Đã hủy', 'class' => 'bg-secondary'],
    ];
@endphp

@extends('layouts.client.master')

@section('title', 'Bán hàng tại quầy')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1 text-success"><i class="fas fa-store me-2"></i>Bán hàng tại quầy</h3>
            <p class="text-muted mb-0">Tạo đơn cho khách mua trực tiếp tại cửa hàng.</p>
        </div>
        <div class="text-end small text-muted">
            <div><i class="fas fa-map-marker-alt me-1"></i>{{ $storeAddress }}</div>
            <div><i class="fas fa-user-shield me-1"></i>Chỉ dành cho nhân viên đã đăng nhập.</div>
        </div>
    </div>

    @php
        $activeTab = request('tab');
        if (!$activeTab) {
            $activeTab = request()->has('orders_page') ? 'orders' : 'pos';
        }
    @endphp

    <ul class="nav nav-pills mb-4" id="offlineTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link text-success {{ $activeTab === 'orders' ? '' : 'active' }}" id="tab-pos-trigger" data-bs-toggle="tab" data-bs-target="#tab-pos" type="button" role="tab" aria-controls="tab-pos" aria-selected="{{ $activeTab === 'orders' ? 'false' : 'true' }}">
                <i class="fas fa-cash-register me-1"></i> Bán hàng tại quầy
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-success {{ $activeTab === 'orders' ? 'active' : '' }}" id="tab-orders-trigger" data-bs-toggle="tab" data-bs-target="#tab-orders" type="button" role="tab" aria-controls="tab-orders" aria-selected="{{ $activeTab === 'orders' ? 'true' : 'false' }}">
                <i class="fas fa-list-ul me-1"></i> Danh sách đơn hàng
            </button>
        </li>
    </ul>

    <div class="tab-content" id="offlineTabsContent">
        <div class="tab-pane fade {{ $activeTab === 'orders' ? '' : 'show active' }}" id="tab-pos" role="tabpanel" aria-labelledby="tab-pos-trigger">
    <div class="row g-4">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bread-slice me-2"></i>Sản phẩm</h5>
                    <div class="input-group input-group-sm" style="max-width: 260px;">
                        <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control border-start-0" id="productSearch" placeholder="Tìm sản phẩm...">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-md-3 g-3" id="productGrid">
                        @forelse($products as $product)
                            <div class="col product-card" data-product="{{ $product['id'] }}" data-name="{{ Str::lower($product['name']) }}">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="ratio ratio-1x1 bg-light rounded-top">
                                        @if($product['image'])
                                            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="rounded-top object-fit-cover">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center text-muted">
                                                <i class="fas fa-image fs-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body d-flex flex-column p-3">
                                        <h6 class="fw-semibold mb-1">{{ $product['name'] }}</h6>
                                        <div class="text-muted small">Mã: {{ $product['code'] ?? '---' }}</div>
                                        <div class="fw-bold text-success my-2">{{ number_format($product['price'], 0, ',', '.') }} ₫</div>
                                        <button class="btn btn-sm btn-outline-success mt-auto" data-add-product="{{ $product['id'] }}"
                                                @if($product['stock'] <= 0) disabled @endif>
                                            <i class="fas fa-cart-plus me-1"></i>{{ $product['stock'] > 0 ? 'Thêm vào đơn' : 'Hết hàng' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning border-0">Chưa có sản phẩm khả dụng.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-receipt me-2 text-primary"></i>Đơn hiện tại</span>
                    <button class="btn btn-outline-danger btn-sm" id="clearOrderBtn"><i class="fas fa-trash me-1"></i>Xóa đơn</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center" style="width: 130px;">Số lượng</th>
                                    <th class="text-end" style="width: 110px;">Thành tiền</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsBody">
                                <tr class="text-muted text-center" id="emptyOrderRow">
                                    <td colspan="4" class="py-4">Chưa chọn sản phẩm nào.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-light rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <span id="orderSubtotal">0 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Giảm giá</span>
                            <span id="orderDiscount">0 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 fw-bold fs-5 text-success">
                            <span>Tổng thanh toán</span>
                            <span id="orderGrandTotal">0 ₫</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1">Tên khách hàng</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Khách lẻ">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label mb-1">Số điện thoại</label>
                            <input type="text" class="form-control" id="customerPhone" placeholder="0900...">
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1">Email (nếu muốn gửi hóa đơn)</label>
                            <input type="email" class="form-control" id="customerEmail" placeholder="khachhang@example.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label mb-1">Ghi chú đơn hàng</label>
                            <textarea class="form-control" id="orderNote" rows="2" placeholder="Ghi chú thêm cho bếp / giao nhận"></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label mb-2">Phương thức thanh toán</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="paymentMethod" id="paymentCash" value="cash" checked>
                            <label class="btn btn-outline-secondary" for="paymentCash"><i class="fas fa-money-bill-wave me-1"></i>Tiền mặt</label>

                            <input type="radio" class="btn-check" name="paymentMethod" id="paymentQr" value="sepay">
                            <label class="btn btn-outline-secondary" for="paymentQr"><i class="fas fa-qrcode me-1"></i>QR code</label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg" id="submitOrderBtn"><i class="fas fa-check me-1"></i>Tạo đơn hàng</button>
                        <button class="btn btn-outline-secondary" id="printOrderBtn" disabled><i class="fas fa-print me-1"></i>In hóa đơn</button>
                    </div>

                    <div class="mt-4 d-none" id="orderResult">
                        <h6 class="fw-bold text-success">Chi tiết đơn vừa tạo</h6>
                        <div class="border rounded p-3 small" id="orderResultBody"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>

        <div class="tab-pane fade {{ $activeTab === 'orders' ? 'show active' : '' }}" id="tab-orders" role="tabpanel" aria-labelledby="tab-orders-trigger">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2 text-primary"></i>Danh sách đơn tại quầy</h5>
            <span class="text-muted small">Hiển thị {{ $recentOrders->perPage() }} đơn mỗi trang</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;">Mã đơn</th>
                            <th>Khách hàng</th>
                            <th class="text-end" style="width: 15%;">Tổng tiền</th>
                            <th style="width: 25%;">Phương thức</th>
                            <th style="width: 15%;">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody id="recentOrdersBody">
                        @foreach ($recentOrders as $order)
                            @php
                                $status = $posFulfillmentStatuses[$order->fulfillment_status] ?? [
                                    'label' => Str::of($order->fulfillment_status ?? '---')->replace('_', ' ')->title(),
                                    'class' => 'bg-secondary',
                                ];
                            @endphp
                            <tr data-order-row>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none fw-semibold">#{{ $order->order_code }}</a>
                                    <div class="small text-muted">{{ optional($order->ordered_at ?? $order->created_at)->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order->customer_name ?? 'Khách lẻ' }}</div>
                                    <div class="small text-muted">{{ $order->customer_phone ?? '---' }}</div>
                                </td>
                                <td class="text-end fw-semibold text-success">{{ number_format($order->grand_total, 0, ',', '.') }} ₫</td>
                                <td>{{ $order->payment_method_label }}</td>
                                <td>
                                    <span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr id="recentOrdersEmpty" class="{{ $recentOrders->isEmpty() ? '' : 'd-none' }}">
                            <td colspan="5" class="text-center text-muted py-4">Chưa có đơn nào được tạo tại quầy.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @if ($recentOrders->hasPages())
            <div class="card-footer bg-white">
                {{ $recentOrders->appends([
                    'tab' => 'orders',
                    'products_page' => request('products_page'),
                ])->fragment('tab-orders')->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
    </div>
</div>

<!-- Modal QR -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalLabel">Thanh toán QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-2 text-muted">Quét mã để thanh toán đơn hàng <strong id="qrOrderCode">#</strong></p>
                <div class="border rounded p-3 d-inline-block mb-3">
                    <img id="qrImage" src="" alt="QR thanh toán" style="width: 260px; height: 260px; object-fit: contain;">
                </div>
                <div class="small text-muted mb-2">Nội dung chuyển khoản: <span class="fw-semibold" id="qrContent">---</span></div>
                <div class="badge bg-info text-dark mb-3">Hết hạn vào: <span id="qrExpire">--:--</span></div>
                <p class="text-muted mb-0">Khi hệ thống ghi nhận thanh toán, đơn sẽ tự chuyển sang trạng thái đã thanh toán.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                <a href="{{ route('admin.offline-orders.index') }}" class="btn btn-success">Tạo đơn mới</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script id="posProductsData" type="application/json">
{!! json_encode($products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>


<script id="posStatusMapData" type="application/json">
{!! json_encode($posFulfillmentStatuses, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script>
(() => {
    const parseJsonElement = (id, fallback) => {
        try {
            const el = document.getElementById(id);
            if (!el) return fallback;
            const text = (el.textContent || '').trim();
            return text ? JSON.parse(text) : fallback;
        } catch (error) {
            console.error(`Khong the phan tich JSON tu #${id}`, error);
            return fallback;
        }
    };

    const products = parseJsonElement('posProductsData', []);
    const storeUrl = "{{ route('admin.offline-orders.store') }}";
    const statusMap = parseJsonElement('posStatusMapData', {});
    const MAX_RECENT_ORDERS = Number(@json($recentOrders->perPage()));

    const paymentStatusBaseUrl = "{{ url('/payment/status') }}/";
    let qrPollTimer = null;

    const state = {
        items: [],
        paymentMethod: 'cash',
    };

    const elements = {
        productSearch: document.getElementById('productSearch'),
        productGrid: document.getElementById('productGrid'),
        orderItemsBody: document.getElementById('orderItemsBody'),
        emptyRow: document.getElementById('emptyOrderRow'),
        orderSubtotal: document.getElementById('orderSubtotal'),
        orderDiscount: document.getElementById('orderDiscount'),
        orderGrandTotal: document.getElementById('orderGrandTotal'),
        submitBtn: document.getElementById('submitOrderBtn'),
        clearBtn: document.getElementById('clearOrderBtn'),
        printBtn: document.getElementById('printOrderBtn'),
        orderResult: document.getElementById('orderResult'),
        orderResultBody: document.getElementById('orderResultBody'),
        customerName: document.getElementById('customerName'),
        customerPhone: document.getElementById('customerPhone'),
        customerEmail: document.getElementById('customerEmail'),
        orderNote: document.getElementById('orderNote'),
        paymentRadios: document.querySelectorAll('input[name="paymentMethod"]'),
        recentOrdersBody: document.getElementById('recentOrdersBody'),
        recentOrdersEmpty: document.getElementById('recentOrdersEmpty'),
    };

    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));
    const qrImage = document.getElementById('qrImage');
    const qrOrderCode = document.getElementById('qrOrderCode');
    const qrContent = document.getElementById('qrContent');
    const qrExpire = document.getElementById('qrExpire');

    const currencyFormatter = new Intl.NumberFormat('vi-VN');

    const humanizeStatus = (value) => {
        if (!value) return '---';
        return value
            .toString()
            .replace(/_/g, ' ')
            .trim()
            .toLowerCase()
            .replace(/\b\w/g, (char) => char.toUpperCase());
    };

    const getStatusMeta = (status) => {
        if (statusMap && Object.prototype.hasOwnProperty.call(statusMap, status)) {
            return statusMap[status];
        }

        return {
            label: humanizeStatus(status),
            class: 'bg-secondary',
        };
    };

    const formatCurrency = (amount) => currencyFormatter.format(Number(amount ?? 0));

    function prependRecentOrder(order) {
        if (!order || !elements.recentOrdersBody) {
            return;
        }

        const statusKey = order.fulfillment_status ?? 'pending';
        const status = getStatusMeta(statusKey);
        const orderUrl = order.order_url || null;
        const createdAt = order.created_at || new Date().toLocaleString('vi-VN');
        const customerName = order.customer_name || 'Khách lẻ';
        const customerPhone = order.customer_phone || '---';
        const paymentLabel = order.payment_method_label || humanizeStatus(order.payment_method);

        const row = document.createElement('tr');
        row.setAttribute('data-order-row', '');
        row.innerHTML = `
            <td>
                ${orderUrl ? `<a href="${orderUrl}" class="text-decoration-none fw-semibold">#${order.order_code ?? '--'}</a>` : `<span class="fw-semibold">#${order.order_code ?? '--'}</span>`}
                <div class="small text-muted">${createdAt}</div>
            </td>
            <td>
                <div class="fw-semibold">${customerName}</div>
                <div class="small text-muted">${customerPhone}</div>
            </td>
            <td class="text-end fw-semibold text-success">${formatCurrency(order.grand_total)} ₫</td>
            <td>${paymentLabel}</td>
            <td><span class="badge ${status.class}">${status.label}</span></td>
        `;

        elements.recentOrdersBody.prepend(row);
        elements.recentOrdersEmpty?.classList.add('d-none');

        const dataRows = elements.recentOrdersBody.querySelectorAll('tr[data-order-row]');
        if (dataRows.length > MAX_RECENT_ORDERS) {
            for (let i = MAX_RECENT_ORDERS; i < dataRows.length; i += 1) {
                dataRows[i].remove();
            }
        }
    }

    const currency = (value) => `${formatCurrency(value)} ₫`;

    function findProduct(productId) {
        return products.find((p) => p.id === productId);
    }

    function renderItems() {
        elements.orderItemsBody.innerHTML = '';

        if (state.items.length === 0) {
            elements.orderItemsBody.appendChild(elements.emptyRow);
            elements.emptyRow.classList.remove('d-none');
        } else {
            elements.emptyRow.classList.add('d-none');
            state.items.forEach((item) => {
                const product = findProduct(item.product_id);
                if (!product) {
                    return;
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <div class="fw-semibold">${product.name}</div>
                        <div class="text-muted small">${currency(product.price)} / ${product.unit_name}</div>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center">
                            <button class="btn btn-outline-secondary" data-change="-1">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" readonly>
                            <button class="btn btn-outline-secondary" data-change="1">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-semibold">${currency(product.price * item.quantity)}</td>
                    <td class="text-end">
                        <button class="btn btn-link text-danger p-0" data-remove="true"><i class="fas fa-times fs-6"></i></button>
                    </td>
                `;

                tr.querySelectorAll('button[data-change]').forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const delta = parseInt(btn.dataset.change, 10);
                        updateQuantity(item.product_id, delta);
                    });
                });

                tr.querySelector('[data-remove]').addEventListener('click', () => {
                    removeItem(item.product_id);
                });

                elements.orderItemsBody.appendChild(tr);
            });
        }

        const totals = calculateTotals();
        elements.orderSubtotal.textContent = currency(totals.subtotal);
        elements.orderDiscount.textContent = currency(0);
        elements.orderGrandTotal.textContent = currency(totals.subtotal);

        elements.submitBtn.disabled = state.items.length === 0;
        elements.clearBtn.disabled = state.items.length === 0;
    }

    function calculateTotals() {
        const subtotal = state.items.reduce((sum, item) => {
            const product = findProduct(item.product_id);
            if (!product) {
                return sum;
            }
            return sum + product.price * item.quantity;
        }, 0);

        return { subtotal };
    }

    function addItem(productId) {
        const product = findProduct(productId);
        if (!product) {
            window.flashToast?.({
                type: 'danger',
                title: 'Không tìm thấy sản phẩm',
                message: 'Sản phẩm bạn chọn không tồn tại.',
            });
            return;
        }

        if (product.stock <= 0) {
            window.flashToast?.({
                type: 'warning',
                title: 'Hết hàng',
                message: 'Sản phẩm này đã hết hàng.',
            });
            return;
        }

        const existing = state.items.find((item) => item.product_id === productId);
        if (existing) {
            if (existing.quantity >= product.stock) {
                window.flashToast?.({
                    type: 'warning',
                    title: 'Vượt tồn kho',
                    message: 'Số lượng không được vượt quá tồn kho hiện tại.',
                });
                return;
            }
            existing.quantity += 1;
        } else {
            state.items.push({
                product_id: productId,
                quantity: 1,
            });
        }

        renderItems();
    }

    function updateQuantity(productId, delta) {
        const product = findProduct(productId);
        const item = state.items.find((i) => i.product_id === productId);
        if (!product || !item) {
            return;
        }

        const nextQty = item.quantity + delta;
        if (nextQty <= 0) {
            removeItem(productId);
            return;
        }

        if (nextQty > product.stock) {
            window.flashToast?.({
                type: 'warning',
                title: 'Vượt tồn kho',
                message: 'Không thể vượt quá số lượng tồn kho hiện tại.',
            });
            return;
        }

        item.quantity = nextQty;
        renderItems();
    }

    function removeItem(productId) {
        state.items = state.items.filter((item) => item.product_id !== productId);
        renderItems();
    }

    function clearOrder() {
        state.items = [];
        renderItems();
        elements.orderResult.classList.add('d-none');
        elements.orderResultBody.innerHTML = '';
        elements.printBtn.disabled = true;
    }

    elements.productGrid.querySelectorAll('[data-add-product]').forEach((btn) => {
        btn.addEventListener('click', () => addItem(parseInt(btn.dataset.addProduct, 10)));
    });

    elements.paymentRadios.forEach((radio) => {
        radio.addEventListener('change', () => {
            state.paymentMethod = radio.value;
        });
    });

    elements.clearBtn.addEventListener('click', () => {
        if (state.items.length === 0) {
            return;
        }
        if (confirm('Bạn chắc chắn muốn xóa toàn bộ sản phẩm trong đơn hiện tại?')) {
            clearOrder();
        }
    });

    elements.productSearch.addEventListener('input', (event) => {
        const keyword = event.target.value.trim().toLowerCase();
        elements.productGrid.querySelectorAll('.product-card').forEach((card) => {
            const match = card.dataset.name.includes(keyword);
            card.classList.toggle('d-none', !match);
        });
    });

    function buildPayload() {
        return {
            customer_name: elements.customerName.value.trim(),
            customer_phone: elements.customerPhone.value.trim(),
            customer_email: elements.customerEmail.value.trim(),
            note: elements.orderNote.value.trim(),
            payment_method: state.paymentMethod,
            items: state.items.map((item) => ({
                product_id: item.product_id,
                quantity: item.quantity,
            })),
        };
    }

    async function submitOrder() {
        if (state.items.length === 0) {
            window.flashToast?.({
                type: 'warning',
                title: 'Chưa có sản phẩm',
                message: 'Vui lòng thêm sản phẩm trước khi tạo đơn.',
            });
            return;
        }

        elements.submitBtn.disabled = true;
        elements.submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(buildPayload()),
            });

            const data = await response.json();

            if (!response.ok || !data.ok) {
                throw new Error(data.message || 'Không thể tạo đơn hàng.');
            }

            window.flashToast?.({
                type: 'success',
                title: 'Thành công',
                message: data.message || 'Đã tạo đơn hàng.',
            });

            renderOrderSummary(data.order);
            prependRecentOrder(data.order);

            document.getElementById('tab-orders-trigger').click();

            elements.printBtn.disabled = false;

            if (data.payment?.qr_url) {
                showQrModal(data.order, data.payment);
            } else {
                clearOrder();
            }
        } catch (error) {
            console.error(error);
            window.flashToast?.({
                type: 'danger',
                title: 'Tạo đơn thất bại',
                message: error.message || 'Vui lòng kiểm tra lại thông tin và thử lại.',
            });
        } finally {
            elements.submitBtn.disabled = false;
            elements.submitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Tạo đơn hàng';
        }
    }

    function renderOrderSummary(order) {
        if (!order) return;
        const itemsHtml = order.items
            .map((item) => `
                <tr>
                    <td>${item.name}</td>
                    <td class="text-center">${item.quantity}</td>
                    <td class="text-end">${currency(item.price)}</td>
                    <td class="text-end">${currency(item.line_total)}</td>
                </tr>
            `)
            .join('');

        elements.orderResultBody.innerHTML = `
            <div class="d-flex justify-content-between mb-2">
                <div>
                    <div><strong>Mã đơn:</strong> #${order.order_code}</div>
                    <div><strong>Khách:</strong> ${order.customer_name}</div>
                </div>
                <div class="text-end">
                    <div><strong>Thanh toán:</strong> ${order.payment_method_label}</div>
                    <div><strong>Trạng thái:</strong> ${order.payment_status === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán'}</div>
                </div>
            </div>
            <table class="table table-sm table-bordered mb-3">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th class="text-center">SL</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>${itemsHtml}</tbody>
            </table>
            <div class="d-flex justify-content-between fw-bold fs-5">
                <span>Tổng cộng</span>
                <span>${currency(order.grand_total)}</span>
            </div>
        `;

        elements.orderResult.classList.remove('d-none');
    }

    function showQrModal(order, payment) {
        qrOrderCode.textContent = '#' + order.order_code;
        qrImage.src = payment.qr_url;
        qrContent.textContent = payment.content ?? order.order_code;
        qrExpire.textContent = payment.expires_at
            ? new Date(payment.expires_at).toLocaleString('vi-VN')
            : 'Không xác định';
        qrModal.show();
        startQrPolling(order);
    }

    function startQrPolling(order) {
        if (!order?.order_code) return;
        const statusUrl = paymentStatusBaseUrl + order.order_code;

        const handlePaid = () => {
            if (qrPollTimer) {
                clearInterval(qrPollTimer);
                qrPollTimer = null;
            }

            const reloadPage = () => window.location.reload();

            if (window.Swal && typeof window.Swal.fire === 'function') {
                window.Swal.fire({
                    icon: 'success',
                    title: 'Hoàn tất!',
                    text: 'Đơn hàng đã được thanh toán thành công.',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                }).then(reloadPage);
            } else {
                alert('Hoàn tất! Đơn hàng đã được thanh toán thành công.');
                reloadPage();
            }
        };

        const poll = async () => {
            try {
                const res = await fetch(statusUrl, { headers: { Accept: 'application/json' } });
                const data = await res.json();
                if (data.ok && data.payment_status === 'paid') {
                    handlePaid();
                }
            } catch (error) {
                console.warn('Payment polling failed', error);
            }
        };

        if (qrPollTimer) {
            clearInterval(qrPollTimer);
        }
        qrPollTimer = setInterval(poll, 4000);
        poll();
    }

    elements.submitBtn.addEventListener('click', submitOrder);

    elements.printBtn.addEventListener('click', () => {
        if (elements.orderResult.classList.contains('d-none')) {
            return;
        }
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>In đơn hàng</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
                </head>
                <body class="p-4">
                    <h4 class="mb-3">Đơn hàng ${qrOrderCode.textContent}</h4>
                    ${elements.orderResultBody.innerHTML}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    });

    renderItems();
})();
</script>
@endpush
