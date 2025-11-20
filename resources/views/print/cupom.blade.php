<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cupom #{{ $sale->id }}</title>
    <style>
        /* Configuração para Impressoras Térmicas (80mm) */
        body {
            font-family: 'Courier New', Courier, monospace; /* Fonte estilo máquina */
            font-size: 12px;
            width: 80mm; /* Largura do papel */
            margin: 0;
            padding: 5px;
            color: #000;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 8px 0; }

        table { width: 100%; font-size: 12px; border-collapse: collapse; }
        td { vertical-align: top; }

        /* Ocultar botão na hora de imprimir */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()"> <div class="center bold">
        PHARMA CONTROL<br>
        SISTEMA DE GESTÃO<br>
    </div>
    <div class="center">
        Rua Exemplo, 123 - Centro<br>
        Tel: (11) 9999-9999
    </div>

    <div class="line"></div>

    <div>
        Venda: #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}<br>
        Data: {{ $sale->created_at->format('d/m/Y H:i') }}<br>
        Cliente: {{ $sale->client_name ?: 'Consumidor Final' }}<br>
        Vendedor: {{ $sale->user->name ?? 'Balcão' }}
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th align="left">DESCRIÇÃO</th>
                <th align="center">QTD</th>
                <th align="right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ substr($item->product->name, 0, 18) }}</td>
                <td align="center">{{ $item->quantity }}</td>
                <td align="right">{{ number_format($item->subtotal, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    <div class="right bold" style="font-size: 14px;">
        TOTAL A PAGAR: R$ {{ number_format($sale->total_amount, 2, ',', '.') }}
    </div>
    <div class="right">
        Pagamento: {{ ucfirst($sale->payment_method) }}
    </div>

    <div class="line"></div>

    <div class="center" style="font-size: 10px;">
        Obrigado pela preferência!<br>
        * Não vale como documento fiscal *
    </div>

    <br>
    <button class="no-print" onclick="window.print()" style="width: 100%; padding: 10px; cursor: pointer; border: 1px solid #000; background: #eee;">
        IMPRIMIR NOVAMENTE
    </button>

</body>
</html>
