<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Services\SaleService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use App\Models\Customer;

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

        $customerId = $this->selectedCustomer ? $this->selectedCustomer->id : null;



        try {
            $saleService->createSale(
                [
                    'client_name' => $this->selectedCustomer ? $this->selectedCustomer->name : ($this->client_name ?? 'Consumidor'),
                    'customer_id' => $customerId,
                    'payment_method' => $this->payment_method
                ],
                $this->items
            );

            $this->reset(['selectedCustomer', 'customerSearch', 'customerResults']);
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

    public $customerSearch = '';
    public $customerResults = [];
    public ?Customer $selectedCustomer = null;

    public $showCustomerModal = false;
    public $new_c_name;
    public $new_c_cpf;
    public $new_c_phone;

    public function updatedCustomerSearch()
    {
        if (strlen($this->customerSearch) < 2) {
            $this->customerResults = [];
            $this->selectedCustomer = null;
            return;
        }

        $this->customerResults = Customer::where('name', 'like', '%' . $this->customerSearch . '%')
            ->orWhere('cpf', 'like', '%' . $this->customerSearch . '%')
            ->take(5)
            ->get();
    }

    public function selectCustomer($id)
    {
        $this->selectedCustomer = Customer::find($id);
        $this->customerSearch = $this->selectedCustomer->name;
        $this->customerResults = [];
    }

    public function saveNewCustomer()
    {
        $this->validate([
            'new_c_name' => 'required|min:3',
            'new_c_cpf'  => 'nullable',
            'new_c_phone'=> 'nullable',
        ]);

        $cpfLimpo = $this->new_c_cpf ? preg_replace('/[^0-9]/', '', $this->new_c_cpf) : null;

        $customer = \App\Models\Customer::create([
            'name'  => strtoupper($this->new_c_name), // Força maiúsculo para ficar bonito no cupom
            'cpf'   => $cpfLimpo,
            'phone' => $this->new_c_phone,
        ]);

        $this->selectedCustomer = $customer;
        $this->customerSearch = $customer->name;

        $this->showCustomerModal = false;
        $this->reset(['new_c_name', 'new_c_cpf', 'new_c_phone']);

        session()->flash('success_modal', 'Cliente cadastrado!');
    }

}
