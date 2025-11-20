<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CreateSale extends Component
{

    public $client_name = '';
    public $payment_method = 'money';

    public $items = [
        ['product_id' => '', 'quantity' => 1]
    ];

    public $allProducts;

    public function mount()
    {
        $this->allProducts = Product::all();
    }

    public function addItem()
    {
        $this->items[] = ['product_id' => '', 'quantity' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save(SaleService $saleService)
    {

        $this->validate([
            'client_name' => 'nullable|string|min:3',
            'payment_method' => 'required',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $saleService->createSale(
                [
                    'client_name' => $this->client_name,
                    'payment_method' => $this->payment_method
                ],
                $this->items
            );

            $this->reset(['items', 'client_name']);
            $this->items = [['product_id' => '', 'quantity' => 1]];

            session()->flash('success', 'Venda realizada com sucesso!');
        } catch (\Exception $e) {
            $this->addError('error_msg', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-sale');
    }
}
