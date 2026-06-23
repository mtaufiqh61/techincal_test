<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'category_id' => 1,
            'name' => 'Laptop Dell',
            'sku' => 'DELL-001',
            'price' => 8000000,
            'stock' => 5
        ]);
        Product::create([
            'category_id' => 1,
            'name' => 'Mouse Logitech',
            'sku' => 'LOG-001',
            'price' => 250000,
            'stock' => 20
        ]);
        Product::create([
            'category_id' => 2,
            'name' => 'Kemeja Polos',
            'sku' => 'KEM-001',
            'price' => 150000,
            'stock' => 30
        ]);
        Product::create([
            'category_id' => 3,
            'name' => 'Kopi Robusta',
            'sku' => 'KOP-001',
            'price' => 50000,
            'stock' => 100
        ]);
    }
}
