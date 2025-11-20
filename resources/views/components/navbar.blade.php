<nav class="bg-white border-b border-gray-100 shadow-sm mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center font-bold text-xl text-indigo-600">
                    💊 PharmaControl
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">

                    <a href="/"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                       {{ request()->is('/') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Dashboard
                    </a>

                    <a href="/venda"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                       {{ request()->is('venda') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        PDV (Venda)
                    </a>

                    @can('manager-access')
                    <a href="{{ route('produtos.index') }}"
                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                    {{ request()->routeIs('produtos.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Produtos
                    </a>


                    <a href="{{ route('vendas.historico') }}"
                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('vendas.historico') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Vendas
                    </a>

                    <a href="{{ route('estoque.entrada') }}"
                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('estoque.entrada') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Entrada de Nota
                    </a>
                    @endcan

                    @can('admin-access')
                    <a href="{{ route('usuarios.index') }}"
                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('usuarios.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Usuários
                    </a>

                    <a href="{{ route('auditoria') }}"
                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('vendas.historico') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Logs
                    </a>
                    @endcan

                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <div class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-green-600 font-bold uppercase">● Online</div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 hover:underline">
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
