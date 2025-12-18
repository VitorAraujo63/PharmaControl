<?php

namespace App\Livewire;

use App\Exports\SalesProfitExport;
use App\Models\SaleItem;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.app')]
class FinancialReport extends Component
{
    public $start_date;

    public $end_date;

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function export()
    {
        return Excel::download(
            new SalesProfitExport($this->start_date, $this->end_date),
            'lucratividade_'.time().'.xlsx'
        );
    }

    public function render()
    {
        $itens = SaleItem::whereHas('sale', function ($q) {
            $q->whereBetween('created_at', [$this->start_date.' 00:00:00', $this->end_date.' 23:59:59'])
                ->where('status', '!=', 'canceled');
        })->with(['batch'])->get();

        $faturamentoTotal = 0;
        $custoTotal = 0;

        foreach ($itens as $item) {
            $faturamentoTotal += $item->subtotal;
            $custoTotal += ($item->batch->cost_price ?? 0) * $item->quantity;
        }

        $lucroReal = $faturamentoTotal - $custoTotal;
        $margemMedia = $faturamentoTotal > 0 ? ($lucroReal / $faturamentoTotal) * 100 : 0;

        return view('livewire.financial-report', [
            'faturamento' => $faturamentoTotal,
            'custo' => $custoTotal,
            'lucro' => $lucroReal,
            'margem' => $margemMedia,
        ]);
    }
}
