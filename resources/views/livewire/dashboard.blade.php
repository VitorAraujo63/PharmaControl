<div>
    <h1 class="text-3xl font-bold mb-8 text-gray-800">Dashboard Financeiro</h1>

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
            <span class="text-xl font-bold">+ Nova Venda (PDV)</span>
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
                    <li class="border-b py-3 flex justify-between items-center">
                        <div>
                            <span class="font-bold block">{{ $venda->client_name ?: 'Cliente Balcão' }}</span>
                            <span class="text-xs text-gray-500">{{ $venda->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="font-mono text-green-600 font-bold">
                            R$ {{ number_format($venda->total_amount, 2, ',', '.') }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
