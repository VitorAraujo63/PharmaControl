@component('layouts.app')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Recuperar Senha</h2>

            @if (session('status'))
                <div class="mb-4 text-green-600 font-bold text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700">E-mail</label>
                    <input type="email" name="email" required class="w-full border p-2 rounded mt-1" placeholder="seu@email.com">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
                    Enviar Link de Recuperação
                </button>
            </form>
        </div>
    </div>
@endcomponent