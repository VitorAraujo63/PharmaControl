<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dipirona = Product::create([
            'name' => 'Dipirona 500mg (Teste PEPS)',
            'sku' => 'DIP-500',
            'description' => 'Analgésico e antitérmico',
            'price' => 10.00,
            'min_stock_alert' => 20,
        ]);

        Batch::create([
            'product_id' => $dipirona->id,
            'batch_code' => 'LOTE-VELHO',
            'quantity' => 5,
            'cost_price' => 4.00,
            'expiration_date' => Carbon::now()->addDays(30),
        ]);

        Batch::create([
            'product_id' => $dipirona->id,
            'batch_code' => 'LOTE-NOVO',
            'quantity' => 10,
            'cost_price' => 4.50,
            'expiration_date' => Carbon::now()->addYear(),
        ]);

        $vitamina = Product::create([
            'name' => 'Vitamina C 1g',
            'sku' => 'VIT-C',
            'description' => 'Imunidade',
            'price' => 25.00,
            'min_stock_alert' => 5,
        ]);

        Batch::create([
            'product_id' => $vitamina->id,
            'batch_code' => 'LOTE-VIT',
            'quantity' => 100,
            'cost_price' => 12.00,
            'expiration_date' => Carbon::now()->addMonths(6),
        ]);

        Product::create([
            'name' => 'Antibiótico Raro (Sem Estoque)',
            'sku' => 'ANT-001',
            'description' => 'Controlado',
            'price' => 80.00,
            'min_stock_alert' => 2,
        ]);

    }
}
