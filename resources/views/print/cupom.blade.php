<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cupom #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 80mm; /* Largura padrão térmica */
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

        /* Esconde o botão na impressão */
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">

    @php
        function formatDocumento($value) {
            $value = preg_replace('/[^0-9]/', '', $value);
            if (strlen($value) === 11) {
                return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
            }
            if (strlen($value) === 14) {
                return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value);
            }
            return $value;
        }
    @endphp

    <div class="center bold">
        PHARMA CONTROL<br>
        SISTEMA DE GESTÃO<br>
    </div>
    <div class="center" style="font-size: 10px;">
        Rua Exemplo, 123 - Centro<br>
        CNPJ: 00.000.000/0001-00
    </div>

    <div class="line"></div>

    <div>
        Venda: #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}<br>
        Data: {{ $sale->created_at->format('d/m/Y H:i') }}<br>
        Vendedor: {{ $sale->user->name ?? 'Balcão' }}
    </div>

    <div class="line"></div>

    <div style="font-size: 12px;">
        @if($sale->customer)
            <strong>CLIENTE:</strong> {{ $sale->customer->name }}<br>

            @if(!empty($sale->customer->cpf))
                <strong>CPF/CNPJ:</strong> {{ formatDocumento($sale->customer->cpf) }}
            @else
                @endif
        @else
            <strong>CLIENTE:</strong> CONSUMIDOR FINAL
        @endif
    </div>
    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th align="left">ITEM</th>
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
        TOTAL: R$ {{ number_format($sale->total_amount, 2, ',', '.') }}
    </div>
    <div class="right" style="font-size: 11px;">
        Pagamento: {{ strtoupper($sale->payment_method) }}
    </div>

    <div class="line"></div>

    <div class="center" style="font-size: 10px;">
        Obrigado pela preferência!<br>
        * Não vale como documento fiscal *
    </div>

    <br>
    <button class="no-print" onclick="window.print()" style="width: 100%; padding: 15px; cursor: pointer; border: 2px solid #000; background: #eee; font-weight: bold;">
        IMPRIMIR NOVAMENTE
    </button>

</body>
</html>
