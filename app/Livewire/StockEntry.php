<?php

namespace App\Livewire;

use App\Models\Batch;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StockEntry extends Component
{
    public $search = '';

    public $searchResults = [];

    public ?Product $selectedProduct = null;

    public $batch_code;

    public $quantity;

    public $expiration_date;

    public $cost_price;

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];

            return;
        }

        $this->searchResults = Product::where('name', 'like', '%'.$this->search.'%')
            ->orWhere('sku', 'like', '%'.$this->search.'%')
            ->take(5)
            ->get();
    }

    public function selectProduct($id)
    {
        $this->selectedProduct = Product::find($id);
        $this->search = $this->selectedProduct->name;
        $this->searchResults = [];
    }

    public function save()
    {

        $this->validate([
            'selectedProduct' => 'required',
            'batch_code' => 'required|string|max:50',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date|after:today',
            'cost_price' => 'required',
        ], [
            'selectedProduct.required' => 'Por favor, pesquise e selecione um produto antes.',
            'expiration_date.after' => 'A validade deve ser uma data futura.',
        ]);

        $cost = str_replace(',', '.', str_replace('.', '', $this->cost_price));

        Batch::create([
            'product_id' => $this->selectedProduct->id,
            'batch_code' => strtoupper($this->batch_code),
            'quantity' => $this->quantity,
            'expiration_date' => $this->expiration_date,
            'cost_price' => $cost,
        ]);

        session()->flash('success', "Estoque adicionado para {$this->selectedProduct->name}!");

        $this->reset(['batch_code', 'quantity', 'expiration_date', 'cost_price', 'selectedProduct', 'search', 'searchResults']);
    }

    public function render()
    {
        return view('livewire.stock-entry');
    }
}
