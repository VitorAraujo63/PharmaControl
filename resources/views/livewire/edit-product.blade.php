<div class="max-w-4xl mx-auto mt-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Editar Produto: {{ $name }}</h1>
        <a href="{{ route('produtos.index') }}" class="text-indigo-600 hover:underline">← Voltar</a>
    </div>

    <form wire:submit.prevent="update" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
                <input wire:model="name" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">SKU</label>
                <input wire:model="sku" type="text" class="uppercase shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100" >
                @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Preço (R$)</label>
                <input wire:model="price" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Alerta de Estoque Mínimo</label>
                <input wire:model="min_stock_alert" type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
            <textarea wire:model="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" rows="3"></textarea>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('produtos.index') }}" class="text-gray-600 hover:text-gray-800">Cancelar</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                Salvar Alterações
            </button>
        </div>
    </form>

    <hr class="my-8 border-gray-300">

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">📦 Gestão de Lotes (Estoque)</h3>

        @if (session()->has('batch_success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('batch_success') }}</div>
        @endif
        @if (session()->has('batch_error'))
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ session('batch_error') }}</div>
        @endif

        <table class="min-w-full leading-normal mb-8">
            <thead>
                <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">
                    <th class="px-5 py-3">Lote</th>
                    <th class="px-5 py-3">Validade</th>
                    <th class="px-5 py-3">Custo</th>
                    <th class="px-5 py-3 text-center">Qtd Atual</th>
                    <th class="px-5 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->batches as $batch)
                    <tr class="border-b border-gray-200">
                        <td class="px-5 py-2 text-sm">{{ $batch->batch_code }}</td>
                        <td class="px-5 py-2 text-sm {{ $batch->expiration_date < now() ? 'text-red-600 font-bold' : '' }}">
                            {{ $batch->expiration_date->format('d/m/Y') }}
                            @if($batch->expiration_date < now()) (Vencido) @endif
                        </td>
                        <td class="px-5 py-2 text-sm">R$ {{ $batch->cost_price }}</td>
                        <td class="px-5 py-2 text-sm text-center font-bold bg-gray-50 rounded">
                            {{ $batch->quantity }}
                        </td>
                        <td class="px-5 py-2 text-sm text-right">
                            <button wire:click="deleteBatch({{ $batch->id }})"
                                    wire:confirm="Tem certeza? Isso removerá este estoque."
                                    class="text-red-500 hover:text-red-700 text-xs font-bold">
                                [Excluir]
                            </button>
                        </td>
                    </tr>
                @endforeach
                @if($product->batches->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Nenhum lote cadastrado (Estoque Zero).</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="bg-indigo-50 p-4 rounded border border-indigo-100">
            <h4 class="font-bold text-indigo-800 mb-2 text-sm">Adicionar Novo Lote / Ajuste de Estoque</h4>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-2 items-end">
                <div>
                    <label class="text-xs font-bold text-gray-600">Código Lote</label>
                    <input wire:model="new_batch_code" type="text" class="w-full border rounded p-1 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">Validade</label>
                    <input wire:model="new_expiration_date" type="date" class="w-full border rounded p-1 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">Qtd</label>
                    <input wire:model="new_quantity" type="number" class="w-full border rounded p-1 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">Custo (Un)</label>
                    <input wire:model="new_cost_price" type="text" class="w-full border rounded p-1 text-sm" placeholder="0.00">
                </div>
                <div>
                    <button wire:click="addBatch" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700 w-full h-[30px]">
                        + Adicionar
                    </button>
                </div>
            </div>
            @error('new_batch_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            @error('new_quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

</div>
</div>
