<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class DefaultCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::firstOrCreate(
            ['slug' => 'chua-xac-dinh'],
            [
                'name' => 'Chưa xác định',
                'short_description' => 'Danh mục mặc định khi sản phẩm chưa được phân loại.',
                'is_visible' => false,
                'display_order' => 999,
            ]
        );
    }
}
