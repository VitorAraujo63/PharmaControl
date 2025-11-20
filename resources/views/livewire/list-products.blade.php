<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestão de Produtos</h1>
        <a href="{{ route('produtos.novo') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded flex items-center">
            + Novo Produto
        </a>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search"
               type="text"
               placeholder="Pesquisar por nome ou SKU..."
               class="w-full md:w-1/3 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Produto / SKU
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Preço
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Stock Total
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div>
                                    <p class="text-gray-900 whitespace-no-wrap font-bold">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-gray-500 whitespace-no-wrap text-xs">
                                        SKU: {{ $product->sku }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                            @php
                                $stock = $product->total_stock;
                                $isLowStock = $stock <= $product->min_stock_alert;
                            @endphp

                            <span class="relative inline-block px-3 py-1 font-semibold {{ $isLowStock ? 'text-red-900' : 'text-green-900' }} leading-tight">
                                <span aria-hidden class="absolute inset-0 {{ $isLowStock ? 'bg-red-200' : 'bg-green-200' }} opacity-50 rounded-full"></span>
                                <span class="relative">{{ $stock }} un.</span>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">

                            <a href="{{ route('produtos.editar', $product->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3 font-bold">
                                Editar
                            </a>

                            <button wire:click="delete({{ $product->id }})"
                                    wire:confirm="Tem a certeza? Se houver vendas, não será excluído."
                                    class="text-red-600 hover:text-red-900">
                                Apagar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $products->links() }}
        </div>
    </div>
</div>
