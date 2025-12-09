<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            2 => 'Bánh Kem',
            3 => 'Bánh Ngọt',
            4 => 'Bánh Mousse Trái Cây',
            5 => 'Bánh Tiramisu',
            6 => 'Tea Break',
            7 => 'Bánh Sinh Nhật',
        ];

        $samples = [
            2 => [
                ['Bánh Kem Dâu Tây', 150000, 120000, 'Bánh kem dâu tươi ngon, mềm mịn và vị chua nhẹ tự nhiên.'],
                ['Bánh Kem Socola Hạnh Nhân', 180000, 150000, 'Bánh kem socola phủ hạnh nhân rang giòn tan.'],
                ['Bánh Kem Bắp Sữa Tươi', 160000, 135000, 'Ngọt dịu với vị sữa và hương thơm đặc trưng của bắp.'],
            ],
            3 => [
                ['Bánh Ngọt Bơ Sữa', 120000, 95000, 'Bánh ngọt mềm xốp, vị bơ sữa đậm đà.'],
                ['Bánh Cupcake Phô Mai', 80000, 70000, 'Cupcake phô mai tan chảy, thơm nức mũi.'],
                ['Bánh Muffin Socola Chip', 95000, 85000, 'Muffin mềm ẩm, đầy ắp socola chip.'],
            ],
            4 => [
                ['Bánh Mousse Chanh Dây', 170000, 140000, 'Mousse vị chanh dây thanh mát, ngọt nhẹ và chua dịu.'],
                ['Bánh Mousse Việt Quất', 175000, 145000, 'Mousse việt quất tươi, vị trái cây tự nhiên.'],
                ['Bánh Mousse Dâu Kem Phô Mai', 180000, 155000, 'Sự kết hợp hoàn hảo giữa kem phô mai và dâu tươi.'],
            ],
            5 => [
                ['Bánh Tiramisu Cà Phê', 190000, 160000, 'Tiramisu truyền thống hương vị cà phê Ý đặc trưng.'],
                ['Bánh Tiramisu Matcha', 200000, 170000, 'Tiramisu trà xanh thanh mát, béo nhẹ, hấp dẫn.'],
                ['Bánh Tiramisu Dâu Tây', 195000, 165000, 'Phiên bản ngọt ngào của tiramisu với dâu tươi.'],
            ],
            6 => [
                ['Tea Break Mini Cupcake', 100000, 85000, 'Cupcake nhỏ gọn, tiện lợi cho tiệc trà.'],
                ['Tea Break Bánh Mặn Phô Mai', 95000, 82000, 'Bánh mặn giòn rụm, vị phô mai tan chảy.'],
                ['Tea Break Bánh Trà Xanh', 98000, 87000, 'Hương vị trà xanh đậm đà, nhẹ nhàng tinh tế.'],
            ],
            7 => [
                ['Bánh Sinh Nhật Dâu Kem', 220000, 190000, 'Bánh sinh nhật vị dâu kem tươi, trang trí đẹp mắt.'],
                ['Bánh Sinh Nhật Socola', 250000, 210000, 'Món bánh sinh nhật truyền thống với lớp phủ socola đậm.'],
                ['Bánh Sinh Nhật Trái Cây Tươi', 260000, 220000, 'Bánh trang trí bằng nhiều loại trái cây tươi.'],
            ],
        ];

        $count = 1;

        foreach ($categories as $catId => $catName) {
            foreach ($samples[$catId] as [$name, $listed, $sale, $desc]) {
                Product::create([
                    'primary_category_id' => $catId,
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . $count,
                    'product_code' => 'SP' . strtoupper(Str::random(8)),
                    'short_description' => $desc,
                    'description' => $desc . ' Được làm thủ công mỗi ngày với nguyên liệu chọn lọc.',
                    'listed_price' => $listed,
                    'sale_price' => $sale,
                    'total_stock' => rand(20, 80),
                    'unit_name' => 'cái',
                    'status' => 'active',
                    'variant_name' => 'Mặc định',
                    'sku' => 'SKU-' . strtoupper(Str::random(6)),
                    'variant_price' => $sale,
                    'variant_stock_quantity' => rand(5, 30),
                    'show_on_homepage' => true,
                    'is_featured' => (bool)rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $count++;
            }
        }
    }
}
