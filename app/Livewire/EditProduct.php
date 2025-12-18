<?php

namespace App\Livewire;

use App\Models\Batch;
use App\Models\Product;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditProduct extends Component
{
    public Product $product;

    public $name;

    public $sku;

    public $description;

    public $price;

    public $min_stock_alert;

    public $new_batch_code;

    public $new_quantity;

    public $new_expiration_date;

    public $new_cost_price;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->name = $product->name;
        $this->sku = $product->sku;
        $this->description = $product->description;
        $this->price = number_format($product->price, 2, ',', '.');
        $this->min_stock_alert = $product->min_stock_alert;
    }

    public function update()
    {
        $formattedPrice = str_replace(',', '.', str_replace('.', '', $this->price));

        $this->validate([
            'name' => 'required|min:3',
            'sku' => ['required', Rule::unique('products', 'sku')->ignore($this->product->id)],
            'price' => 'required',
            'min_stock_alert' => 'integer|min:0',
        ]);

        $this->product->update([
            'name' => $this->name,
            'sku' => strtoupper($this->sku),
            'description' => $this->description,
            'price' => $formattedPrice,
            'min_stock_alert' => $this->min_stock_alert,
        ]);

        session()->flash('success', 'Produto atualizado com sucesso!');

        return redirect()->route('produtos.index');
    }

    public function addBatch()
    {
        $this->validate([
            'new_batch_code' => 'required|string',
            'new_quantity' => 'required|integer|min:1',
            'new_expiration_date' => 'required|date',
            'new_cost_price' => 'nullable',
        ]);

        Batch::create([
            'product_id' => $this->product->id,
            'batch_code' => strtoupper($this->new_batch_code),
            'quantity' => $this->new_quantity,
            'expiration_date' => $this->new_expiration_date,
            'cost_price' => $this->new_cost_price ? str_replace(',', '.', $this->new_cost_price) : 0,
        ]);

        $this->reset(['new_batch_code', 'new_quantity', 'new_expiration_date', 'new_cost_price']);

        $this->product->refresh();

        session()->flash('batch_success', 'Lote adicionado com sucesso!');
    }

    public function deleteBatch($batchId)
    {
        $batch = Batch::find($batchId);

        if (! $batch) {
            return;
        }

        if (\App\Models\SaleItem::where('batch_id', $batchId)->exists()) {
            session()->flash('batch_error', 'Não é possível excluir este lote pois já existem vendas dele.');

            return;
        }

        $batch->delete();
        $this->product->refresh();
        session()->flash('batch_success', 'Lote removido!');
    }

    public function render()
    {
        return view('livewire.edit-product');
    }
}
