<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class CupomController extends Controller
{
    public function imprimir($id)
    {
        $sale = Sale::with(['items.product', 'user'])->findOrFail($id);

        return view('print.cupom', compact('sale'));
    }
}
