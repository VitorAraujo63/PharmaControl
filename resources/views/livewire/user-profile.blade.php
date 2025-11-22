<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Minha Conta</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">Dados Pessoais</h2>

                @if (session()->has('profile_success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('profile_success') }}</div>
                @endif

                <form wire:submit.prevent="updateProfile">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Nome</label>
                            <input wire:model="name" type="text" class="w-full border rounded p-2">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">E-mail</label>
                            <input wire:model="email" type="email" class="w-full border rounded p-2">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Salvar Alterações
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">Alterar Senha</h2>

                @if (session()->has('password_success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('password_success') }}</div>
                @endif

                <form wire:submit.prevent="updatePassword">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Senha Atual</label>
                            <input wire:model="current_password" type="password" class="w-full border rounded p-2">
                            @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Nova Senha</label>
                            <input wire:model="password" type="password" class="w-full border rounded p-2">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700">Confirmar Nova Senha</label>
                            <input wire:model="password_confirmation" type="password" class="w-full border rounded p-2">
                        </div>
                    </div>
                    <button type="submit" class="mt-4 bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-900">
                        Atualizar Senha
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-indigo-50 p-6 rounded-lg shadow border border-indigo-100">
                <h2 class="text-xl font-bold text-indigo-900 mb-4 border-b border-indigo-200 pb-2">
                    Segurança (2FA)
                </h2>

                <livewire:two-factor-manage />

            </div>
        </div>

    </div>
</div>
