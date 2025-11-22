<div>
    @if(auth()->user()->google2fa_secret)

        <div class="text-center py-4">
            <div class="flex items-center justify-center gap-2 text-green-600 font-bold text-lg mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Proteção Ativa
            </div>
            <p class="text-gray-600 mb-6 text-sm">
                Sua conta exige um código do celular para entrar.
            </p>

            <button wire:click="disableTwoFactor"
                    wire:confirm="Tem certeza? Isso deixará sua conta menos segura."
                    class="text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 px-4 py-2 rounded text-sm font-bold transition">
                Desativar 2FA
            </button>
        </div>

    @else

        @if(!$showQrCode)
            <div class="text-center">
                <p class="text-gray-600 mb-6 text-sm">
                    Proteja sua conta exigindo um código do Google Authenticator ao fazer login.
                </p>
                <button wire:click="enableTwoFactor" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-bold text-sm w-full">
                    Configurar Agora
                </button>
            </div>
        @else
            <div class="text-center animate-fade-in-down">
                <p class="mb-4 font-bold text-gray-700 text-sm">1. Escaneie no App:</p>

                <div class="inline-block border p-2 bg-white shadow-sm mb-4">
                    {!! $qrCodeSvg !!}
                </div>

                <p class="mb-2 font-bold text-gray-700 text-sm">2. Digite o código:</p>

                <div class="flex justify-center gap-2 mb-4">
                    <input wire:model="code" type="text" class="border border-gray-300 rounded px-3 py-2 text-center text-lg tracking-widest w-32 focus:border-indigo-500 outline-none" placeholder="000000" maxlength="6">
                </div>

                @error('code')
                    <span class="text-red-500 block mb-4 font-bold text-xs">{{ $message }}</span>
                @enderror

                <div class="flex flex-col gap-2">
                    <button wire:click="confirmTwoFactor" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-bold text-sm">
                        Ativar
                    </button>
                    <button wire:click="$set('showQrCode', false)" class="text-gray-500 text-xs hover:underline">
                        Cancelar
                    </button>
                </div>
            </div>
        @endif

    @endif
</div>
