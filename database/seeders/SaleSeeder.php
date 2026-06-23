<?php

namespace Database\Seeders;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $sale = Sale::create([
            'user_id' => $user->id,
            'sale_date' => now()->toDateString(),
            'total' => 0
        ]);

        $item1 = SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => 1,
            'quantity' => 1,
            'price' => 8000000,
            'subtotal' => 8000000
        ]);

        $item2 = SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => 2,
            'quantity' => 2,
            'price' => 250000,
            'subtotal' => 500000
        ]);

        $sale->update(['total' => 8500000]);
    }
}
