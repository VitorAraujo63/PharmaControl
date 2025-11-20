<div class="max-w-3xl mx-auto py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-2">Entrada de Estoque</h1>
    <p class="text-gray-500 mb-8">Registre a chegada de mercadorias e novos lotes.</p>

    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate-pulse">
            <p class="font-bold">Sucesso!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg p-6">

        <div class="relative mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-2">Qual produto chegou?</label>

            <div class="relative">
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       class="w-full border-2 border-gray-300 rounded-lg p-3 pl-10 text-lg focus:border-indigo-500 focus:ring-0"
                       placeholder="Digite o nome ou SKU..."
                       autocomplete="off">

                <div class="absolute left-3 top-4 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            @if(count($searchResults) > 0)
                <ul class="absolute z-50 bg-white border border-gray-200 w-full mt-1 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                    @foreach($searchResults as $result)
                        <li wire:click="selectProduct({{ $result->id }})"
                            class="p-3 hover:bg-indigo-50 cursor-pointer border-b last:border-0 flex justify-between items-center">
                            <div>
                                <span class="font-bold text-gray-800 block">{{ $result->name }}</span>
                                <span class="text-xs text-gray-500">SKU: {{ $result->sku }}</span>
                            </div>
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">
                                Estoque Atual: {{ $result->total_stock }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        @if($selectedProduct)
            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-6 animate-fade-in-down">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-indigo-800 text-lg">
                        Adicionando estoque em: <span class="underline">{{ $selectedProduct->name }}</span>
                    </h3>
                    <button wire:click="$set('selectedProduct', null)" class="text-xs text-indigo-500 hover:underline">
                        Alterar produto
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Lote (da Caixa)</label>
                        <input wire:model="batch_code" type="text" class="w-full border rounded p-2 uppercase">
                        @error('batch_code') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Validade</label>
                        <input wire:model="expiration_date" type="date" class="w-full border rounded p-2">
                        @error('expiration_date') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Quantidade (Unidades)</label>
                        <input wire:model="quantity" type="number" class="w-full border rounded p-2 font-bold text-indigo-900">
                        @error('quantity') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Custo Unitário (R$)</label>
                        <input wire:model="cost_price" type="text" class="w-full border rounded p-2" placeholder="0,00">
                        @error('cost_price') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <button wire:click="save" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                        Confirmar Entrada
                    </button>
                </div>
            </div>
        @else
            @if(strlen($search) > 2 && count($searchResults) == 0)
                <div class="text-center text-gray-500 py-8">
                    Nenhum produto encontrado. <br>
                    <a href="{{ route('produtos.novo') }}" class="text-indigo-600 underline">Cadastrar novo produto?</a>
                </div>
            @else
                <div class="text-center text-gray-400 py-8 bg-gray-50 rounded border border-dashed border-gray-300">
                    Pesquise um produto acima para começar a entrada.
                </div>
            @endif
        @endif

    </div>
</div>
