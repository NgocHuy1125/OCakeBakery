<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Coupon;
use App\Models\CustomerAddress;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Promotion;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $this->seedPaymentMethods();
        $this->seedSiteSettings();
        $this->seedDemoData();
    }

    protected function seedUsers(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@kimloan.cake'],
            [
                'customer_code' => 'ADMIN001',
                'full_name' => 'Quản trị hệ thống',
                'phone_number' => '0900123456',
                'password' => Hash::make('Admin!234'),
                'status' => 'active',
                'role' => 'admin',
                'registered_at' => now(),
                'email_verified' => true,
            ]
        );

        if (!$admin->role || $admin->role !== 'admin') {
            $admin->forceFill(['role' => 'admin'])->save();
        }

        $staff = User::firstOrCreate(
            ['email' => 'staff@kimloan.cake'],
            [
                'customer_code' => 'STAFF001',
                'full_name' => 'Nhân viên cửa hàng',
                'phone_number' => '0900222333',
                'password' => Hash::make('Staff!234'),
                'status' => 'active',
                'role' => 'staff',
                'registered_at' => now(),
            ]
        );

        if (!$staff->role || $staff->role !== 'staff') {
            $staff->forceFill(['role' => 'staff'])->save();
        }
    }

    protected function seedPaymentMethods(): void
    {
        $methods = [
            [
                'method_code' => 'cod',
                'name' => 'Thanh toán khi nhận hàng',
                'description' => 'Thanh toán trực tiếp bằng tiền mặt khi nhận hàng.',
                'method_type' => 'offline',
                'configuration' => null,
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'method_code' => 'transfer',
                'name' => 'Chuyển khoản ngân hàng',
                'description' => 'Chuyển khoản tới tài khoản được chỉ định.',
                'method_type' => 'offline',
                'configuration' => [
                    'bank' => env('PAY_PARTNER_BANK'),
                    'account_no' => env('PAY_ACCOUNT_NO'),
                    'account_name' => env('PAY_ACCOUNT_NAME'),
                ],
                'display_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['method_code' => $method['method_code']],
                $method
            );
        }
    }

    protected function seedSiteSettings(): void
    {
        $settings = [
            [
                'setting_key' => 'brand.name',
                'setting_value' => 'KimLoan Cake',
                'description' => 'Tên thương hiệu hiển thị trên website.',
                'group_key' => 'branding',
            ],
            [
                'setting_key' => 'brand.hotline',
                'setting_value' => '0900 123 456',
                'description' => 'Hotline tư vấn khách hàng.',
                'group_key' => 'branding',
            ],
            [
                'setting_key' => 'store.address',
                'setting_value' => '123 Trần Hưng Đạo, Quận 1, TP.HCM',
                'description' => 'Địa chỉ cửa hàng chính.',
                'group_key' => 'store',
            ],
            [
                'setting_key' => 'payment.qr_info',
                'setting_value' => 'VCB - 0123456789 - KIM LOAN CAKE',
                'description' => 'Thông tin tài khoản nhận thanh toán QR.',
                'group_key' => 'payment',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(['setting_key' => $setting['setting_key']], $setting);
        }
    }

    protected function seedDemoData(): void
    {
        /** @var User $customer */
        $customer = User::updateOrCreate(
            ['email' => 'khachhang@kimloan.cake'],
            [
                'customer_code' => 'CUST001',
                'full_name' => 'Nguyễn Thị Kim Loan',
                'phone_number' => '0900111222',
                'password' => Hash::make('Customer!234'),
                'status' => 'active',
                'role' => 'customer',
                'registered_at' => now(),
                'email_verified' => true,
            ]
        );

        CustomerAddress::updateOrCreate(
            ['user_id' => $customer->id, 'address_line' => '73/34 đường Đặng Dung'],
            [
                'label' => 'Nhà riêng',
                'receiver_name' => $customer->full_name,
                'receiver_phone' => $customer->phone_number,
                'receiver_email' => $customer->email,
                'district_code' => 'Q1',
                'district_name' => 'Quận 1',
                'ward_code' => 'Q1-W1',
                'ward_name' => 'Phường Tân Định',
                'note' => 'Liên hệ trước khi giao.',
                'is_default' => true,
            ]
        );

        $category = \App\Models\Category::updateOrCreate(
            ['slug' => 'banh-kem-truyen-thong'],
            [
                'name' => 'Bánh kem truyền thống',
                'short_description' => 'Những chiếc bánh kem thơm ngon cho mọi dịp lễ.',
                'is_visible' => true,
            ]
        );

        $product = Product::updateOrCreate(
            ['slug' => 'banh-kem-dau-tay'],
            [
                'primary_category_id' => $category->id,
                'product_code' => 'CAKE001',
                'name' => 'Bánh kem dâu tây',
                'short_description' => 'Bánh kem mềm mịn phủ dâu tươi hấp dẫn.',
                'description' => 'Lớp kem whipping béo ngậy kết hợp mứt dâu tây, phù hợp sinh nhật và tiệc nhỏ.',
                'listed_price' => 450000,
                'sale_price' => 420000,
                'total_stock' => 20,
                'status' => 'active',
                'show_on_homepage' => true,
                'is_featured' => true,
            ]
        );

        $variant = ProductVariant::updateOrCreate(
            ['product_id' => $product->id, 'variant_name' => 'Size 18cm'],
            [
                'sku' => 'CAKE001-18',
                'price' => 450000,
                'sale_price' => 420000,
                'stock_quantity' => 12,
                'status' => 'active',
                'is_default' => true,
            ]
        );

        ProductImage::updateOrCreate(
            ['product_id' => $product->id, 'image_url' => '/images/demo/cake-strawberry.jpg'],
            [
                'alt_text' => 'Bánh kem dâu tây',
                'is_primary' => true,
                'display_order' => 1,
            ]
        );

        $promotion = Promotion::updateOrCreate(
            ['promotion_code' => 'WELCOMETAKE10'],
            [
                'title' => 'Ưu đãi khách hàng mới 10%',
                'promotion_type' => 'percent',
                'value' => 10,
                'max_discount_value' => 50000,
                'conditions' => 'Áp dụng cho đơn hàng đầu tiên, tối thiểu 300.000đ.',
                'usage_limit' => 100,
                'used_count' => 0,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
                'show_badge' => true,
            ]
        );
        $promotion->products()->syncWithoutDetaching([$product->id => ['display_order' => 1]]);

        Coupon::updateOrCreate(
            ['coupon_code' => 'FREESHIP20'],
            [
                'title' => 'Freeship đơn từ 200k',
                'discount_type' => 'amount',
                'discount_value' => 30000,
                'minimum_order_value' => 200000,
                'issued_quantity' => 200,
                'used_quantity' => 0,
                'members_only' => true,
                'starts_at' => now()->subWeek(),
                'ends_at' => now()->addMonths(2),
                'status' => 'active',
            ]
        );

        Banner::updateOrCreate(
            ['title' => 'Ưu đãi mùa lễ hội'],
            [
                'description' => 'Giảm 15% cho combo bánh tiệc cưới.',
                'image_url' => '/images/demo/banner-festival.jpg',
                'link_url' => '/promotions',
                'position' => 'homepage',
                'display_order' => 1,
                'is_visible' => true,
            ]
        );

        Testimonial::updateOrCreate(
            ['customer_name' => 'Trần Minh'],
            [
                'content' => 'Bánh kem của KimLoan rất ngon và giao hàng đúng hẹn, nhân viên tư vấn nhiệt tình.',
                'job_title' => 'Nhà thiết kế nội thất',
                'display_order' => 1,
                'is_visible' => true,
            ]
        );

        \App\Models\PostCategory::updateOrCreate(
            ['slug' => 'bi-quyet-lam-banh'],
            ['name' => 'Bí quyết làm bánh', 'description' => 'Kinh nghiệm làm bánh tại nhà.']
        );
    }
}
