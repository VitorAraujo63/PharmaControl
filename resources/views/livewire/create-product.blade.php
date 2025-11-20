<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Cadastro de Produto</h1>
        <a href="/" class="text-indigo-600 hover:underline">← Voltar ao Dashboard</a>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p class="font-bold">Sucesso!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <form wire:submit.prevent="save" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

        <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">📦 Informações Básicas</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nome do Medicamento/Produto</label>
                <input wire:model="name" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">SKU (Código Interno)</label>
                <input wire:model="sku" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase">
                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Preço de Venda (R$)</label>
                <input wire:model="price" type="text" placeholder="0,00" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Descrição (Opcional)</label>
            <textarea wire:model="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3"></textarea>
        </div>

        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 mb-6">
            <div class="flex items-center mb-4">
                <input wire:model.live="add_initial_stock" type="checkbox" id="stock" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="stock" class="ml-2 block text-sm font-bold text-gray-900">
                    Deseja adicionar o estoque inicial agora?
                </label>
            </div>

            @if($add_initial_stock)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 transition-all duration-300">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Código do Lote</label>
                        <input wire:model="batch_code" type="text" class="bg-white shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        @error('batch_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Validade</label>
                        <input wire:model="expiration_date" type="date" class="bg-white shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        @error('expiration_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Quantidade</label>
                        <input wire:model="initial_quantity" type="number" class="bg-white shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Custo de Compra (R$)</label>
                        <input wire:model="cost_price" type="text" placeholder="0,00" class="bg-white shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                Salvar Produto
            </button>
        </div>
    </form>
</div>
