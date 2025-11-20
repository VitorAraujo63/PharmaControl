<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">👮 Logs de Auditoria</h1>

    <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex gap-4">
        <div class="w-1/3">
            <label class="block text-xs font-bold text-gray-600 mb-1">Funcionário</label>
            <select wire:model.live="user_id" class="w-full border rounded px-3 py-2 text-sm">
                <option value="">Todos</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-1/3">
            <label class="block text-xs font-bold text-gray-600 mb-1">Ação</label>
            <select wire:model.live="action" class="w-full border rounded px-3 py-2 text-sm">
                <option value="">Todas</option>
                <option value="CREATE">Criação</option>
                <option value="UPDATE">Edição</option>
                <option value="DELETE">Exclusão</option>
            </select>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Data/Hora</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Usuário</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Ação</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold text-gray-600 uppercase">Detalhes (O que mudou)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-3 text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-5 py-3 font-bold text-gray-700">
                            {{ $log->user->name ?? 'Sistema/Desconhecido' }}
                            <div class="text-xs text-gray-400 font-normal">{{ $log->ip_address }}</div>
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $colors = [
                                    'CREATE' => 'bg-green-100 text-green-800',
                                    'UPDATE' => 'bg-blue-100 text-blue-800',
                                    'DELETE' => 'bg-red-100 text-red-800',
                                ];
                                $color = $colors[$log->action] ?? 'bg-gray-100';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $color }}">
                                {{ $log->action }}: {{ $log->model }} #{{ $log->model_id }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs font-mono text-gray-600 break-all">
                            @if(is_array($log->details))
                                @foreach($log->details as $key => $value)
                                    <span class="font-bold text-gray-800">{{ $key }}:</span>
                                    {{ is_array($value) ? json_encode($value) : $value }} <br>
                                @endforeach
                            @else
                                {{ $log->details }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
