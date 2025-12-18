<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ListProducts extends Component
{
    use WithPagination;

    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if (! $product) {
            return;
        }

        if ($product->saleItems()->exists()) {
            session()->flash('error', 'Não é possível excluir este produto pois ele já possui vendas registradas.');

            return;
        }

        $product->delete();
        session()->flash('success', 'Produto eliminado com sucesso.');
    }

    public function render()
    {
        $products = Product::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('sku', 'like', '%'.$this->search.'%')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.list-products', [
            'products' => $products,
        ]);
    }
}
