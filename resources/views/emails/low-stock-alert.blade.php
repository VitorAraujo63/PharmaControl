<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h2 { color: #d9534f; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .urgent { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Relatório de Vencimentos - PharmaControl</h1>
    <p>Olá, Admin. Abaixo estão os lotes que precisam de atenção imediata.</p>

    @if($expiring14->isNotEmpty())
        <h2>🚨 Urgente: Vencem em até 14 dias</h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Lote</th>
                    <th>Qtd</th>
                    <th>Validade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expiring14 as $batch)
                    <tr>
                        <td>{{ $batch->product->name }}</td>
                        <td>{{ $batch->batch_code }}</td>
                        <td>{{ $batch->quantity }}</td>
                        <td class="urgent">{{ $batch->expiration_date->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($expiring30->isNotEmpty())
        <h2>⚠️ Atenção: Vencem em até 30 dias</h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Lote</th>
                    <th>Qtd</th>
                    <th>Validade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expiring30 as $batch)
                    <tr>
                        <td>{{ $batch->product->name }}</td>
                        <td>{{ $batch->batch_code }}</td>
                        <td>{{ $batch->quantity }}</td>
                        <td>{{ $batch->expiration_date->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p>Acesse o sistema para realizar promoções ou baixas.</p>
</body>
</html>
