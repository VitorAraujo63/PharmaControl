<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public ?Sale $vendaSelecionada = null;

    public function render()
    {


        $vendas = Sale::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $vendas->pluck('date')->map(fn($date) => date('d/m', strtotime($date)));

        $values = $vendas->pluck('total');

        $faturamentoHoje = Sale::whereDate('created_at', now())->sum('total_amount');
        $qtdVendasHoje = Sale::whereDate('created_at', now())->count();
        $ultimasVendas = Sale::latest()->take(5)->get();

        return view('livewire.dashboard', [
            'labels' => $labels,
            'values' => $values,
            'faturamentoHoje' => $faturamentoHoje,
            'qtdVendasHoje' => $qtdVendasHoje,
            'ultimasVendas' => $ultimasVendas
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
