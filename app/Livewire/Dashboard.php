<?php

namespace App\Livewire;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public ?Sale $vendaSelecionada = null;

    public function render()
    {
        // 1. Gráficos (Mantido)
        $vendas = Sale::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->where('status', '!=', 'canceled')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $vendas->pluck('date')->map(fn ($date) => date('d/m', strtotime($date)));
        $values = $vendas->pluck('total');

        // 2. Cards de Hoje (Mantido)
        $faturamentoHoje = Sale::whereDate('created_at', now())
            ->where('status', '!=', 'canceled')
            ->sum('total_amount');

        $qtdVendasHoje = Sale::whereDate('created_at', now())
            ->where('status', '!=', 'canceled')
            ->count();

        // 3. OTIMIZAÇÃO 1: Listagem de Vendas com Eager Loading (Isso estava certo!)
        // O with('user') evita que o sistema faça uma consulta para cada venda.
        $ultimasVendas = Sale::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 4. CORREÇÃO DA OTIMIZAÇÃO 2: Estoque
        // Como não existe a coluna 'total_stock', usamos o withSum.
        // O Laravel faz o SQL somar os lotes, o que é muito mais rápido que trazer os dados.
        
        $queryBaixoEstoque = Product::withSum('batches', 'quantity')
            // O havingRaw filtra o resultado da soma feita pelo banco
            ->havingRaw('COALESCE(batches_sum_quantity, 0) <= min_stock_alert');

        $produtosBaixoEstoque = (clone $queryBaixoEstoque)
            ->orderBy('batches_sum_quantity', 'asc')
            ->take(10)
            ->get();

        // Usamos a mesma query base para contar (evita repetir lógica)
        // Nota: count() com having requer um tratamento especial no Laravel, 
        // então aqui vamos fazer do jeito mais seguro para evitar erro de SQL no count:
        $qtdAlertas = Product::withSum('batches', 'quantity')
            ->havingRaw('COALESCE(batches_sum_quantity, 0) <= min_stock_alert')
            ->count(); // O Laravel é inteligente e adapta o count para aggregates recentes

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
