<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Batch; 
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class CreateProduct extends Component
{

    public $name;
    public $sku;
    public $description;
    public $price;
    public $min_stock_alert = 10;


    public $add_initial_stock = false;
    public $initial_quantity;
    public $cost_price;
    public $expiration_date;
    public $batch_code;

    public function save()
    {
        $this->price = str_replace(',', '.', str_replace('.', '', $this->price));

        if ($this->cost_price) {
            $this->cost_price = str_replace(',', '.', str_replace('.', '', $this->cost_price));
        }

        $this->validate([
            'name' => 'required|min:3',
            'sku' => 'required|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'min_stock_alert' => 'integer|min:0',

            'initial_quantity' => 'required_if:add_initial_stock,true|nullable|integer|min:1',
            'cost_price' => 'required_if:add_initial_stock,true|nullable|numeric|min:0',
            'expiration_date' => 'required_if:add_initial_stock,true|nullable|date|after:today',
            'batch_code' => 'required_if:add_initial_stock,true|nullable|string',
        ]);

        $product = Product::create([
            'name' => $this->name,
            'sku' => strtoupper($this->sku),
            'description' => $this->description,
            'price' => $this->price,
            'min_stock_alert' => $this->min_stock_alert,
        ]);

        if ($this->add_initial_stock) {
            Batch::create([
                'product_id' => $product->id,
                'batch_code' => strtoupper($this->batch_code),
                'quantity' => $this->initial_quantity,
                'cost_price' => $this->cost_price,
                'expiration_date' => $this->expiration_date,
            ]);
        }
        session()->flash('success', "Produto '{$product->name}' cadastrado com sucesso!");
        $this->reset();
    }

    public function render()
    {
        return view('livewire.create-product');
    }
}
