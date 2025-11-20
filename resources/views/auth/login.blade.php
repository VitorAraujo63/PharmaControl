<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PharmaControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white rounded-lg shadow-md overflow-hidden">

        <!-- Cabeçalho -->
        <div class="bg-indigo-600 p-6 text-center">
            <h1 class="text-white text-2xl font-bold">💊 PharmaControl</h1>
            <p class="text-indigo-200 text-sm mt-1">Acesso Restrito a Funcionários</p>
        </div>

        <!-- Formulário -->
        <div class="p-8">

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    <strong>Ops!</strong> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                        E-mail Corporativo
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                        placeholder="seu.nome@farmacia.com" required autofocus>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Senha
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="mr-2 text-indigo-600 focus:ring-indigo-500">
                        Lembrar-me
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-200 shadow-lg">
                    ENTRAR
                </button>
            </form>
        </div>

        <div class="bg-gray-50 px-8 py-4 text-center text-xs text-gray-500 border-t">
            &copy; {{ date('Y') }} PharmaControl System. Todos os direitos reservados.
        </div>
    </div>

</body>
</html>
