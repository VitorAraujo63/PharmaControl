<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Histórico de Vendas</h1>

        <div class="bg-indigo-100 text-indigo-800 px-4 py-2 rounded-lg font-bold">
            Total no Período: R$ {{ number_format($totalPeriodo, 2, ',', '.') }}
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1">Data Início</label>
            <input wire:model.live="start_date" type="date" class="border rounded px-3 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-600 mb-1">Data Fim</label>
            <input wire:model.live="end_date" type="date" class="border rounded px-3 py-2 text-sm w-full">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-600 mb-1">Buscar Cliente ou ID</label>
            <input wire:model.live.debounce.300ms="search_client" type="text" placeholder="Nome do cliente..." class="border rounded px-3 py-2 text-sm w-full">
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Data</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Cliente</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Vendedor</th>
                    <th class="px-5 py-3 border-b-2 text-right text-xs font-semibold text-gray-600 uppercase">Valor</th>
                    <th class="px-5 py-3 border-b-2 text-center text-xs font-semibold text-gray-600 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 border-b text-sm font-mono text-gray-500">
                            #{{ $sale->id }}
                            @if($sale->status === 'canceled')
                                <span class="text-xs text-red-600 font-bold ml-2">[CANCELADA]</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 border-b text-sm">
                            {{ $sale->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-4 border-b text-sm font-bold text-gray-800">
                            {{ $sale->client_name ?: 'Consumidor Final' }}
                        </td>
                        <td class="px-5 py-4 border-b text-sm text-gray-600">
                            {{ $sale->user->name ?? 'N/A' }}
                        </td>
                        <td class="px-5 py-4 border-b text-sm text-right font-bold text-green-600">
                            R$ {{ number_format($sale->total_amount, 2, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 border-b text-sm text-center">
                            <button wire:click="verDetalhes({{ $sale->id }})" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs uppercase">
                                Detalhes
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $sales->links() }}
        </div>
    </div>

    @if($vendaSelecionada)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden animate-fade-in-down">

                <div class="bg-indigo-600 p-4 flex justify-between items-center text-white">
                    <h3 class="font-bold">Venda #{{ $vendaSelecionada->id }}</h3>
                    <button wire:click="fecharDetalhes" class="text-2xl hover:text-gray-300">&times;</button>
                </div>

                <div class="p-6 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm bg-gray-50 p-4 rounded">
                        <div><strong>Cliente:</strong> {{ $vendaSelecionada->client_name }}</div>
                        <div><strong>Data:</strong> {{ $vendaSelecionada->created_at->format('d/m/Y H:i') }}</div>
                        <div><strong>Vendedor:</strong> {{ $vendaSelecionada->user->name ?? '-' }}</div>
                        <div><strong>Pagamento:</strong> {{ ucfirst($vendaSelecionada->payment_method) }}</div>
                    </div>

                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600">
                            <tr>
                                <th class="py-2 px-2">Produto</th>
                                <th class="py-2 px-2">Lote</th>
                                <th class="py-2 px-2 text-right">Qtd</th>
                                <th class="py-2 px-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendaSelecionada->items as $item)
                                <tr class="border-b">
                                    <td class="py-2 px-2">{{ $item->product->name }}</td>
                                    <td class="py-2 px-2 text-xs font-mono">{{ $item->batch->batch_code ?? 'N/A' }}</td>
                                    <td class="py-2 px-2 text-right">{{ $item->quantity }}</td>
                                    <td class="py-2 px-2 text-right font-bold">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-100 p-4 flex justify-between items-center">

                    @if($vendaSelecionada->status !== 'canceled')
                        <button wire:click="cancelarVenda({{ $vendaSelecionada->id }})"
                                wire:confirm="Tem certeza absoluta? Isso devolverá os itens ao estoque."
                                class="bg-red-100 text-red-700 hover:bg-red-200 border border-red-300 px-4 py-2 rounded font-bold text-sm">
                            ⛔ Cancelar Venda (Estornar)
                        </button>
                    @else
                        <span class="text-red-600 font-bold italic">Venda Cancelada em {{ $vendaSelecionada->updated_at->format('d/m/Y') }}</span>
                    @endif

                    <a href="{{ route('venda.cupom', $vendaSelecionada->id) }}"
                        target="_blank"
                        class="bg-gray-700 text-white hover:bg-gray-800 px-4 py-2 rounded font-bold text-sm flex items-center gap-2 mr-2">
                            Imprimir Cupom
                        </a>

                    <div class="text-right">
                        <span class="text-xl font-bold {{ $vendaSelecionada->status === 'canceled' ? 'text-gray-400 line-through' : 'text-green-600' }} mr-4">
                            Total: R$ {{ number_format($vendaSelecionada->total_amount, 2, ',', '.') }}
                        </span>

                        <button wire:click="fecharDetalhes" class="bg-gray-500 text-white px-4 py-2 rounded">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
