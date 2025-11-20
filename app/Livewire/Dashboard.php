<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Sale;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        // 1. Agrupa vendas por dia dos últimos 7 dias
        // Isso é SQL puro, muito mais rápido e robusto.
        $vendas = Sale::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Prepara os arrays para o gráfico
        // Ex: ['2023-11-01', '2023-11-02']
        $labels = $vendas->pluck('date')->map(fn($date) => date('d/m', strtotime($date)));

        // Ex: [500.00, 120.50]
        $values = $vendas->pluck('total');

        // 3. KPIs Normais
        $faturamentoHoje = Sale::whereDate('created_at', now())->sum('total_amount');
        $qtdVendasHoje = Sale::whereDate('created_at', now())->count();
        $ultimasVendas = Sale::latest()->take(5)->get();

        return view('livewire.dashboard', [
            'labels' => $labels,   // Mandando só os rótulos
            'values' => $values,   // Mandando só os valores
            'faturamentoHoje' => $faturamentoHoje,
            'qtdVendasHoje' => $qtdVendasHoje,
            'ultimasVendas' => $ultimasVendas
        ]);
    }
}
