<?php

namespace App\Livewire;

use App\Models\Sale;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public ?Sale $vendaSelecionada = null;

    public function render()
    {

        $vendas = \App\Models\Sale::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->where('status', '!=', 'canceled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $vendas->pluck('date')->map(fn ($date) => date('d/m', strtotime($date)));
        $values = $vendas->pluck('total');

        $faturamentoHoje = \App\Models\Sale::whereDate('created_at', now())
            ->where('status', '!=', 'canceled')
            ->sum('total_amount');

        $qtdVendasHoje = \App\Models\Sale::whereDate('created_at', now())
            ->where('status', '!=', 'canceled')
            ->count();

        $ultimasVendas = \App\Models\Sale::latest()->take(5)->get();

        $produtosBaixoEstoque = \App\Models\Product::withSum('batches', 'quantity')
            ->havingRaw('COALESCE(batches_sum_quantity, 0) <= min_stock_alert')
            ->orderBy('batches_sum_quantity', 'asc')
            ->take(10)->get();

        $qtdAlertas = \App\Models\Product::withSum('batches', 'quantity')
            ->havingRaw('COALESCE(batches_sum_quantity, 0) <= min_stock_alert')
            ->count();

        return view('livewire.dashboard', [
            'labels' => $labels,
            'values' => $values,
            'faturamentoHoje' => $faturamentoHoje,
            'qtdVendasHoje' => $qtdVendasHoje,
            'ultimasVendas' => $ultimasVendas,
            'produtosBaixoEstoque' => $produtosBaixoEstoque,
            'qtdAlertas' => $qtdAlertas,
        ]);
    }

    public function verDetalhes($id)
    {
        $this->vendaSelecionada = Sale::with(['items.product', 'user'])->find($id);
    }

    public function fecharDetalhes()
    {
        $this->vendaSelecionada = null;
    }
}
