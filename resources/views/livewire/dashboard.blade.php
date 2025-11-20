<div>
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Dashboard Financeiro</h1>

    @if($qtdAlertas > 0)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded shadow-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="ml-3 w-full">
                    <h3 class="text-lg leading-6 font-medium text-red-800">
                        Atenção: {{ $qtdAlertas }} Produtos com Estoque Baixo
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p class="mb-2">Os seguintes itens atingiram ou estão abaixo do nível mínimo de reposição:</p>

                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded border border-red-200">
                                <thead class="bg-red-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-bold uppercase text-red-800">Produto</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-red-800">Mínimo</th>
                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase text-red-800">Atual</th>
                                        <th class="px-4 py-2 text-right text-xs font-bold uppercase text-red-800">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produtosBaixoEstoque as $prod)
                                        <tr class="border-t border-red-100 hover:bg-red-50">
                                            <td class="px-4 py-2 font-medium">{{ $prod->name }}</td>
                                            <td class="px-4 py-2 text-center text-gray-500">{{ $prod->min_stock_alert }}</td>
                                            <td class="px-4 py-2 text-center font-bold text-red-600">
                                                {{ $prod->batches_sum_quantity ?? 0 }}
                                            </td>
                                            <td class="px-4 py-2 text-right">
                                                <a href="{{ route('produtos.editar', $prod->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold underline">
                                                    Repor
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($qtdAlertas > 10)
                            <p class="mt-2 text-xs text-red-500 italic">E mais {{ $qtdAlertas - 10 }} produtos...</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
            <h2 class="text-gray-500 text-sm uppercase font-bold">Faturamento Hoje</h2>
            <p class="text-3xl font-bold text-green-600 mt-2">
                R$ {{ number_format($faturamentoHoje, 2, ',', '.') }}
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
            <h2 class="text-gray-500 text-sm uppercase font-bold">Vendas Hoje</h2>
            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $qtdVendasHoje }}
            </p>
        </div>

        <a href="/venda" class="bg-indigo-600 p-6 rounded-lg shadow-md text-white flex items-center justify-center hover:bg-indigo-700 transition">
            <span class="text-xl font-bold">+ Nova Venda</span>
        </a>
    </div>

    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Histórico de Faturamento</h2>

        <div class="relative h-64 w-full"
            x-data='{
                init() {
                    new Chart(this.$refs.canvas, {
                        type: "bar",
                        data: {
                            labels: @json($labels),
                            datasets: [{
                                label: "Faturamento (R$)",
                                data: @json($values),
                                backgroundColor: "#4F46E5",
                                borderRadius: 4,
                                barThickness: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                }
            }'>
            <canvas x-ref="canvas"></canvas>
        </div>
    </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Últimas Transações</h2>
    <ul>
        @foreach($ultimasVendas as $venda)
            <li class="border-b py-3 flex justify-between items-center hover:bg-gray-50 p-2 rounded cursor-pointer"
                wire:click="verDetalhes({{ $venda->id }})"> <div>
                    <span class="font-bold block">{{ $venda->client_name ?: 'Cliente Balcão' }}</span>
                    <div class="text-xs text-gray-500">
                        Vendedor: {{ $venda->user->name ?? 'Sistema' }} • {{ $venda->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="text-right">
                    <span class="block font-mono text-green-600 font-bold">
                        R$ {{ number_format($venda->total_amount, 2, ',', '.') }}
                    </span>
                    <span class="text-xs text-blue-500 underline">Ver detalhes</span>
                </div>
            </li>
        @endforeach
    </ul>
</div>

    @if($vendaSelecionada)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl overflow-hidden">

                <div class="bg-indigo-600 p-4 flex justify-between items-center text-white">
                    <h3 class="font-bold text-lg">Detalhes da Venda #{{ $vendaSelecionada->id }}</h3>
                    <button wire:click="fecharDetalhes" class="text-2xl hover:text-gray-300">&times;</button>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <p class="text-gray-500">Cliente</p>
                            <p class="font-bold">{{ $vendaSelecionada->client_name ?? 'Não informado' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Vendedor</p>
                            <p class="font-bold">{{ $vendaSelecionada->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Data</p>
                            <p class="font-bold">{{ $vendaSelecionada->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Pagamento</p>
                            <p class="font-bold uppercase">{{ $vendaSelecionada->payment_method }}</p>
                        </div>
                    </div>

                    <h4 class="font-bold border-b pb-2 mb-2">Itens Comprados</h4>
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="text-gray-500">
                                <th class="py-1">Produto</th>
                                <th class="py-1">Lote Usado</th>
                                <th class="py-1 text-right">Qtd</th>
                                <th class="py-1 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendaSelecionada->items as $item)
                                <tr class="border-b last:border-0">
                                    <td class="py-2">{{ $item->product->name }}</td>
                                    <td class="py-2 text-xs font-mono text-gray-500">{{ $item->batch->batch_code ?? 'Lote Antigo' }}</td>
                                    <td class="py-2 text-right">{{ $item->quantity }}</td>
                                    <td class="py-2 text-right font-bold">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6 text-right text-2xl font-bold text-green-600">
                        Total: R$ {{ number_format($vendaSelecionada->total_amount, 2, ',', '.') }}
                    </div>
                </div>

                <div class="bg-gray-100 p-4 text-right">
                    <button wire:click="fecharDetalhes" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
