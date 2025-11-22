<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <div class="text-4xl mb-4">🔐</div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Verificação de Segurança</h2>
        <p class="text-gray-600 mb-6">Abra seu Google Authenticator e digite o código.</p>

        <form wire:submit.prevent="verify">
            <input wire:model="code" type="text"
                   class="w-full text-center text-2xl tracking-[0.5em] border-2 border-gray-300 rounded-lg p-3 mb-4 focus:border-indigo-500 outline-none"
                   placeholder="000000" autofocus>

            @error('code') <div class="text-red-500 mb-4 font-bold">{{ $message }}</div> @enderror

            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded hover:bg-indigo-700">
                Verificar
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:underline">
                Sair / Trocar conta
            </button>
        </form>
    </div>
</div>
