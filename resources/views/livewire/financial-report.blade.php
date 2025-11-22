<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📈 Relatório de Lucratividade Real</h1>

        <button wire:click="export" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Baixar Excel Completo
        </button>
    </div>

    <div class="bg-white p-4 rounded-lg shadow mb-8 flex gap-4 items-end">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">De:</label>
            <input wire:model.live="start_date" type="date" class="border rounded p-2">
        </div>
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Até:</label>
            <input wire:model.live="end_date" type="date" class="border rounded p-2">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-lg shadow border-t-4 border-blue-500">
            <p class="text-gray-500 text-sm font-bold uppercase">Faturamento Bruto</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">R$ {{ number_format($faturamento, 2, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Total vendido no caixa</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-t-4 border-orange-500">
            <p class="text-gray-500 text-sm font-bold uppercase">Custo da Mercadoria (CMV)</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">R$ {{ number_format($custo, 2, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">Baseado no custo dos lotes</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-t-4 border-green-500 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-green-100 rounded-full opacity-50"></div>
            <p class="text-gray-500 text-sm font-bold uppercase">Lucro Líquido Real</p>
            <p class="text-3xl font-bold text-green-600 mt-2">R$ {{ number_format($lucro, 2, ',', '.') }}</p>
            <p class="text-xs text-green-600 font-bold mt-1">Dinheiro no bolso</p>
        </div>

        <div class="bg-gray-800 p-6 rounded-lg shadow text-white">
            <p class="text-gray-400 text-sm font-bold uppercase">Margem de Lucro</p>
            <p class="text-3xl font-bold text-yellow-400 mt-2">{{ number_format($margem, 1, ',', '.') }}%</p>
            <p class="text-xs text-gray-400 mt-1">Saúde do negócio</p>
        </div>

    </div>

    <div class="mt-8 text-center text-gray-500 text-sm">
        * Dados baseados nos lançamentos de entrada de estoque. Vendas sem custo de lote cadastrado contam custo como R$ 0,00.
    </div>
</div>
