<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE products MODIFY sale_price DECIMAL(15,2) NULL');
        DB::statement('ALTER TABLE product_variants MODIFY sale_price DECIMAL(15,2) NULL');
    }

    public function down(): void
    {
        DB::statement('UPDATE products SET sale_price = listed_price WHERE sale_price IS NULL');
        DB::statement('UPDATE product_variants SET sale_price = price WHERE sale_price IS NULL');

        DB::statement('ALTER TABLE products MODIFY sale_price DECIMAL(15,2) NOT NULL');
        DB::statement('ALTER TABLE product_variants MODIFY sale_price DECIMAL(15,2) NOT NULL');
    }
};
