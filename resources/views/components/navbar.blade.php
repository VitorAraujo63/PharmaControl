<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-xl font-bold text-indigo-600 hover:text-indigo-700 transition">
                        <span>💊</span>
                        <span>PharmaControl</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">

                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out h-full
                       {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('venda') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out h-full
                       {{ request()->routeIs('venda') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        PDV (Venda)
                    </a>

                    @can('manager-access')
                        <div class="relative h-full flex items-center" x-data="{ openDropdown: false }">
                            <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition h-full
                                    {{ request()->routeIs('produtos.*') || request()->routeIs('estoque.*') ? 'text-indigo-600 border-indigo-500' : '' }}">
                                <span>Estoque</span>
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="openDropdown"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute left-0 top-14 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                                 style="display: none;">

                                <a href="{{ route('produtos.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Lista de Produtos
                                </a>
                                <a href="{{ route('estoque.entrada') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Entrada de Nota
                                </a>
                            </div>
                        </div>
                    @endcan

                    @can('manager-access')
                        <div class="relative h-full flex items-center" x-data="{ openDropdown: false }">
                            <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition h-full
                                    {{ request()->routeIs('vendas.*') || request()->routeIs('relatorios.*') ? 'text-indigo-600 border-indigo-500' : '' }}">
                                <span>Financeiro</span>
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="openDropdown"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute left-0 top-14 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                                 style="display: none;">

                                <a href="{{ route('vendas.historico') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Histórico de Vendas
                                </a>
                                <a href="{{ route('relatorios.financeiro') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Relatório de Lucro
                                </a>
                            </div>
                        </div>
                    @endcan

                    @can('admin-access')
                        <div class="relative h-full flex items-center" x-data="{ openDropdown: false }">
                            <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition h-full
                                    {{ request()->routeIs('usuarios.*') || request()->routeIs('auditoria') ? 'text-indigo-600 border-indigo-500' : '' }}">
                                <span>Sistema</span>
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="openDropdown"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute left-0 top-14 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                                 style="display: none;">

                                <a href="{{ route('usuarios.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Gestão de Usuários
                                </a>
                                <a href="{{ route('auditoria') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logs de alteração
                                </a>
                            </div>
                        </div>
                    @endcan

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ openUser: false }">
                    <button @click="openUser = !openUser" @click.away="openUser = false" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                        <div class="text-right mr-2">
                            <div class="font-bold text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-400 uppercase">{{ Auth::user()->role }}</div>
                        </div>
                        <div class="ml-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="openUser"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                         style="display: none;">

                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Meu Perfil
                        </a>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">
                                Sair
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
