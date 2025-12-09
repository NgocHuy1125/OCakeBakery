<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận thanh toán</title>
    <style>
        body {
            background-color: #f6f6f6;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            max-width: 640px;
            margin: 0 auto;
            padding: 32px 20px;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }
        .card-header {
            background: linear-gradient(135deg, #16a34a, #22c55e);
            color: #ffffff;
            padding: 28px 32px;
        }
        .card-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        .card-body {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .summary {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 20px;
            margin: 24px 0;
        }
        .summary strong {
            display: inline-block;
            min-width: 150px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items thead tr {
            background: #f1f5f9;
        }
        .items th,
        .items td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        .items th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }
        .items td {
            font-size: 15px;
        }
        .total-line {
            font-weight: 700;
            font-size: 17px;
            color: #16a34a;
        }
        .btn {
            display: inline-block;
            background: #16a34a;
            color: #ffffff !important;
            padding: 14px 28px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 24px;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #64748b;
            margin-top: 32px;
        }
        .btn-wrapper {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="card-header">
                <h1>Kim Loan Cake · Xác nhận thanh toán</h1>
            </div>
            <div class="card-body">
                <p class="greeting">Chào {{ $order->customer_name }},</p>
                <p>Cảm ơn bạn đã hoàn tất thanh toán cho đơn hàng <strong>#{{ $order->order_code }}</strong>.</p>
                <p>Chúng tôi sẽ chuẩn bị đơn hàng và liên hệ để giao tới bạn trong thời gian sớm nhất.</p>

                <div class="summary">
                    <p><strong>Mã đơn:</strong> #{{ $order->order_code }}</p>
                    <p><strong>Ngày thanh toán:</strong> {{ optional($order->paid_at)->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</p>
                    <p><strong>Số tiền đã nhận:</strong> {{ number_format($order->grand_total, 0, ',', '.') }} ₫</p>
                    <p><strong>Hình thức:</strong> Thanh toán qua SePay (chuyển khoản QR)</p>
                </div>

                <h3 style="margin-bottom: 12px;">Chi tiết đơn hàng</h3>
                <table class="items">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th style="width: 70px;">SL</th>
                            <th style="width: 120px;">Đơn giá</th>
                            <th style="width: 140px;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    {{ $item->product_name_snapshot }}
                                    @if(!empty($item->variant_name_snapshot))
                                        <div style="color:#64748b; font-size:13px;">{{ $item->variant_name_snapshot }}</div>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->sale_price ?? $item->list_price, 0, ',', '.') }} ₫</td>
                                <td>{{ number_format($item->line_total, 0, ',', '.') }} ₫</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:600;">Tạm tính</td>
                            <td>{{ number_format($order->subtotal_amount, 0, ',', '.') }} ₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:600;">Phí vận chuyển</td>
                            <td>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:600;">Giảm giá</td>
                            <td>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</td>
                        </tr>
                        <tr class="total-line">
                            <td colspan="3" style="text-align:right;">Tổng thanh toán</td>
                            <td>{{ number_format($order->grand_total, 0, ',', '.') }} ₫</td>
                        </tr>
                    </tbody>
                </table>

                <h3 style="margin-top: 28px; margin-bottom: 12px;">Thông tin giao hàng</h3>
                <p><strong>Người nhận:</strong> {{ $order->customer_name }} · {{ $order->customer_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address_line }}, {{ $order->ward_name }}, {{ $order->district_name }}</p>
                @if(!empty($order->customer_note))
                    <p><strong>Ghi chú của bạn:</strong> {{ $order->customer_note }}</p>
                @endif

                <div class="btn-wrapper">
                    <a href="{{ $ctaUrl }}" class="btn">Xem chi tiết đơn hàng</a>
                </div>

                <p style="margin-top: 24px;">Nếu cần hỗ trợ thêm, bạn vui lòng phản hồi email này hoặc liên hệ hotline <strong>0862 427 713</strong>.</p>
            </div>
        </div>
        <div class="footer">
            © {{ now()->year }} Kim Loan Cake. Tất cả các quyền được bảo lưu.<br>
            90 Độc Lập, Tân Phú, TP.HCM
        </div>
    </div>
</body>
</html>
