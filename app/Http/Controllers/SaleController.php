<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SaleService;
use Illuminate\Http\Request;
use Exception;

class SaleController extends Controller
{
    protected $saleService;


    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'client_name' => 'nullable|string',
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {

            $sale = $this->saleService->createSale(
                [
                    'client_name' => $request->client_name,
                    'payment_method' => $request->payment_method
                ],
                $request->items
            );

            return redirect()->back()->with('success', "Venda #{$sale->id} realizada com sucesso!");

        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
