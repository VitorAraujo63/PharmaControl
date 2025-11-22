<div>
    <h1 class="text-2xl font-bold mb-6">Nova Venda (PDV)</h1>

    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @error('error_msg')
        <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
            {{ $message }}
        </div>
    @enderror

    <form wire:submit.prevent="save">

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="mb-6 relative">
                <label class="block font-bold mb-2 text-gray-700" name='cliente'>Cliente</label>

                <div class="relative">
                    <input type="text" name='cliente'
                        wire:model.live.debounce.300ms="customerSearch"
                        class="w-full border p-2 rounded pl-10 {{ $selectedCustomer ? 'bg-green-50 border-green-500 text-green-700 font-bold' : '' }}"
                        placeholder="Busque por nome ou CPF...">

                    <div class="absolute left-3 top-2.5 text-gray-400">
                        @if($selectedCustomer)
                            <span class="text-green-600">✓</span>
                        @else
                            <img src="{{ asset('/img/lupa.svg') }}" width='20px' alt="" name='cliente'>
                        @endif
                    </div>

                    @if($selectedCustomer)
                        <button type="button" wire:click="$set('selectedCustomer', null); $set('customerSearch', '')"
                                class="absolute right-3 top-2 text-red-400 hover:text-red-600 font-bold">
                            ✕
                        </button>
                    @endif
                </div>

                @if(strlen($customerSearch) >= 2 && empty($selectedCustomer))
                    <div class="absolute z-10 bg-white border border-gray-200 w-full mt-1 rounded shadow-xl">
                        @if(count($customerResults) > 0)
                            <ul>
                                @foreach($customerResults as $c)
                                    <li wire:click="selectCustomer({{ $c->id }})"
                                        class="p-3 hover:bg-indigo-50 cursor-pointer border-b last:border-0 flex justify-between">
                                        <span class="font-bold">{{ $c->name }}</span>
                                        <span class="text-gray-500 text-sm">{{ $c->cpf ?? 'Sem CPF' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="p-4 text-center text-gray-500">
                                <p class="mb-2">Nenhum cliente encontrado.</p>
                                <button type="button" wire:click="$set('showCustomerModal', true)"
                                        class="text-indigo-600 underline font-bold hover:text-indigo-800">
                                    Cadastrar novo cliente?
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            @if($showCustomerModal)
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden animate-fade-in-down">

                        <div class="bg-indigo-600 p-4 text-white flex justify-between items-center">
                            <h3 class="font-bold text-lg">Novo Cliente Rápido</h3>
                            <button wire:click="$set('showCustomerModal', false)" class="text-2xl hover:text-gray-300">&times;</button>
                        </div>

                        <div class="p-6">
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Nome Completo</label>
                                <input wire:model="new_c_name" type="text" class="w-full border rounded p-2" autofocus>
                                @error('new_c_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">CPF</label>
                                <input wire:model="new_c_cpf" x-mask="999.999.999-99" type="text" class="w-full border rounded p-2">
                                @error('new_c_cpf') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-1">Celular / WhatsApp</label>
                                <input wire:model="new_c_phone" x-mask="(99) 99999-9999" type="text" class="w-full border rounded p-2">
                            </div>

                            <div class="flex justify-end gap-2 mt-6">
                                <button wire:click="$set('showCustomerModal', false)" class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                                <button wire:click="saveNewCustomer" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold shadow">
                                    Salvar e Selecionar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div>
                <label class="block font-bold">Forma de Pagamento</label>
                <select wire:model="payment_method" class="w-full border p-2 rounded">
                    <option value="Dinheiro">Dinheiro</option>
                    <option value="Cartão de crédito">Cartão de Crédito</option>
                    <option value="Pix">PIX</option>
                </select>
            </div>
        </div>

        <hr class="my-4">

        <h3 class="text-lg font-bold mb-2">Produtos</h3>

        @foreach ($items as $index => $item)
            <div class="flex gap-4 mb-2 items-end">

                <div class="flex-1">
                    <label class="text-sm">Produto</label>
                    <select wire:model="items.{{ $index }}.product_id" class="w-full border p-2 rounded">
                        <option value="">Selecione...</option>
                        @foreach($allProducts as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} (Estoque: {{ $product->total_stock }})
                            </option>
                        @endforeach
                    </select>
                    @error("items.{$index}.product_id") <span class="text-red-500 text-sm">Obrigatório</span> @enderror
                </div>

                <div class="w-32">
                    <label class="text-sm">Qtd</label>
                    <input type="number" wire:model="items.{{ $index }}.quantity" class="w-full border p-2 rounded">
                    @error("items.{$index}.quantity") <span class="text-red-500 text-sm">Inválido</span> @enderror
                </div>

                <button type="button" wire:click="removeItem({{ $index }})" class="bg-red-500 text-white px-3 py-2 rounded">
                    X
                </button>
            </div>
        @endforeach

        <div class="mt-4 flex justify-between">
            <button type="button" wire:click="addItem" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + Adicionar Produto
            </button>

            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700">
                Finalizar Venda
            </button>
        </div>
    </form>
</div>
