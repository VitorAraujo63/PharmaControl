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
            <div>
                <label class="block font-bold">Nome do Cliente</label>
                <input type="text" wire:model="client_name" class="w-full border p-2 rounded">
            </div>
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
