<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class SaleService
{
    public function createSale(array $saleData, array $items): Sale
    {
        return DB::transaction(function () use ($saleData, $items) {

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => $saleData['customer_id'] ?? null,
                'client_name' => $saleData['client_name'] ?? 'Consumidor Final',
                'payment_method' => $saleData['payment_method'],
                'total_amount' => 0,
            ]);

            $totalSaleAmount = 0;

           foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qtyNeeded = $item['quantity'];

               if ($product->total_stock < $qtyNeeded) {
                    throw new Exception("Estoque insuficiente para o produto: {$product->name}");
                }

               $batches = $product->batches()
                    ->available() // Aquele Scope que criamos no Model
                    ->orderBy('expiration_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($qtyNeeded <= 0) break; // Já pegamos tudo que precisava

                   $quantityToTake = min($qtyNeeded, $batch->quantity);

                   $sale->items()->create([
                        'product_id' => $product->id,
                        'batch_id' => $batch->id, // Rastreabilidade total!
                        'quantity' => $quantityToTake,
                        'unit_price' => $product->price,
                        'subtotal' => $quantityToTake * $product->price,
                    ]);

                   $batch->decrement('quantity', $quantityToTake);

                    $totalSaleAmount += ($quantityToTake * $product->price);
                    $qtyNeeded -= $quantityToTake;
                }

                if ($qtyNeeded > 0) {
                    throw new Exception("Erro de integridade de estoque no produto: {$product->name}");
                }
            }

            $sale->update(['total_amount' => $totalSaleAmount]);

            return $sale;
        });
    }

    public function cancelSale(Sale $sale): Sale
    {
        if ($sale->status === 'canceled') {
            throw new Exception("Esta venda já foi cancelada anteriormente.");
        }

        return DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $item->batch->increment('quantity', $item->quantity);
            }

            $sale->update(['status' => 'canceled']);

            return $sale;
        });
    }
}
