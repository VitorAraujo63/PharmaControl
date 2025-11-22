<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestão de Usuários</h1>
        <button wire:click="create" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2">
            + Novo Funcionário
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">{{ session('error') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Buscar por nome ou e-mail..." class="w-full md:w-1/3 border rounded py-2 px-3 shadow-sm">
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold">Nome / E-mail</th>
                    <th class="px-5 py-3 text-center font-semibold">Cargo (Permissão)</th>
                    <th class="px-5 py-3 text-center font-semibold">Status</th>
                    <th class="px-5 py-3 text-center font-semibold">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <p class="font-bold text-gray-900">{{ $user->name }}</p>
                            <p class="text-gray-500">{{ $user->email }}</p>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @php
                                $badges = [
                                    'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'manager' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'cashier' => 'bg-gray-100 text-gray-800 border-gray-200',
                                ];
                                $labels = [
                                    'admin' => 'Administrador',
                                    'manager' => 'Gerente',
                                    'cashier' => 'Vendedor',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badges[$user->role] ?? '' }}">
                                {{ $labels[$user->role] ?? ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($user->status === 'ativo')
                                <span class="px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-800">Ativo</span>
                            @else
                                <span class="px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-800">Inativo</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button wire:click="edit({{ $user->id }})" class="text-blue-600 hover:text-blue-900 font-bold mr-3">Editar</button>

                            @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})"
                                        wire:confirm="Tem certeza? O usuário perderá o acesso."
                                        class="text-red-600 hover:text-red-900 font-bold">
                                    Excluir
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">{{ $users->links() }}</div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden animate-fade-in-down">
                <div class="bg-indigo-600 p-4 text-white flex justify-between items-center">
                    <h3 class="font-bold text-lg">
                        {{ $user_id_editing ? 'Editar Usuário' : 'Novo Usuário' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-2xl hover:text-gray-300">&times;</button>
                </div>

                <form wire:submit.prevent="save" class="p-6">

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nome Completo</label>
                        <input wire:model="name" type="text" class="w-full border rounded p-2">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 mb-1">E-mail</label>
                        <input wire:model="email" type="email" class="w-full border rounded p-2">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Cargo</label>

                        @php
                            $isSelf = $user_id_editing === auth()->id();
                        @endphp

                        <select wire:model="role" class="w-full border rounded p-2 {{ $isSelf ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}" @disabled($isSelf)>
                            <option value="cashier">Vendedor</option>
                            <option value="manager">Gerente</option>
                            <option value="admin">Administrador</option>
                        </select>

                        @if($isSelf)
                            <span class="text-xs text-orange-600 block mt-1">Você não pode alterar seu próprio cargo.</span>
                        @endif

                        @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>

                            <select wire:model="status" class="w-full border rounded p-2 {{ $isSelf ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}" @disabled($isSelf)>
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo (Bloqueado)</option>
                            </select>

                            @if($isSelf)
                                <span class="text-xs text-orange-600 block mt-1">Você não pode se desativar.</span>
                            @endif

                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Senha
                            @if($user_id_editing) <span class="text-gray-400 font-normal text-xs">(Deixe em branco para manter)</span> @endif
                        </label>
                        <input wire:model="password" type="password" class="w-full border rounded p-2" placeholder="••••••••">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-300 rounded text-gray-700 hover:bg-gray-400">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-bold">
                            Salvar Dados
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

</div>
