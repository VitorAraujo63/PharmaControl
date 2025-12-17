@component('layouts.app')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Definir Nova Senha</h2>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-4">
                    <label class="block text-gray-700">E-mail</label>
                    <input type="email" name="email" required class="w-full border p-2 rounded mt-1" 
                           value="{{ request()->email }}"> @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Nova Senha</label>
                    <input type="password" name="password" required class="w-full border p-2 rounded mt-1">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">Confirmar Nova Senha</label>
                    <input type="password" name="password_confirmation" required class="w-full border p-2 rounded mt-1">
                </div>

                <button type="submit" class="w-full bg-green-600 text-white p-2 rounded hover:bg-green-700">
                    Alterar Senha
                </button>
            </form>
        </div>
    </div>
@endcomponent