<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Sale;
use App\Services\SaleService;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('layouts.app')]
class SalesHistory extends Component
{
    use WithPagination;

    public $start_date;
    public $end_date;
    public $search_client = '';

    public ?Sale $vendaSelecionada = null;

    public function mount()
    {
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updatedStartDate() { $this->resetPage(); }
    public function updatedEndDate() { $this->resetPage(); }
    public function updatedSearchClient() { $this->resetPage(); }

    public function verDetalhes($id)
    {
        $this->vendaSelecionada = Sale::with(['items.product', 'user', 'items.batch'])->find($id);
    }

    public function fecharDetalhes()
    {
        $this->vendaSelecionada = null;
    }

    public function render()
    {
        $sales = Sale::query()
            ->with('user')
            ->whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->where(function($query) {
                $query->where('client_name', 'like', '%'.$this->search_client.'%')
                      ->orWhere('id', 'like', '%'.$this->search_client.'%');
            })
            ->latest()
            ->paginate(15);


        $totalPeriodo = Sale::whereDate('created_at', '>=', $this->start_date)
            ->whereDate('created_at', '<=', $this->end_date)
            ->sum('total_amount');

        return view('livewire.sales-history', [
            'sales' => $sales,
            'totalPeriodo' => $totalPeriodo
        ]);
    }

    public function cancelarVenda($saleId, SaleService $service)
    {
        try {
            $sale = Sale::find($saleId);

            if (!$sale) return;

            $service->cancelSale($sale);

            $this->vendaSelecionada = null;
            session()->flash('success', "Venda #{$saleId} cancelada e estoque estornado com sucesso!");

        } catch (\Exception $e) {
            session()->flash('error', "Erro ao cancelar: " . $e->getMessage());
        }
    }
}
