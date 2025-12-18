<?php

namespace App\Exports;

use App\Models\SaleItem;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use \Illuminate\Database\Eloquent\Builder as builder;

class SalesProfitExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $startDate;

    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query(): builder
    {
        return SaleItem::query()
            ->with(['sale', 'product', 'batch'])
            ->whereHas('sale', function ($query) {
                $query->whereBetween('created_at', [$this->startDate.' 00:00:00', $this->endDate.' 23:59:59'])
                    ->where('status', '!=', 'canceled');
            });
    }

    public function headings(): array
    {
        return [
            'Data Venda',
            'Venda #',
            'Produto',
            'Qtd',
            'Preço Venda (Un)',
            'Custo Lote (Un)',
            'Subtotal Venda',
            'Custo Total',
            'LUCRO REAL',
            'Margem %',
        ];
    }

    public function map($item): array
    {
        $custoUn = $item->batch->cost_price ?? 0;
        $custoTotal = $custoUn * $item->quantity;
        $lucro = $item->subtotal - $custoTotal;

        $margem = $item->subtotal > 0 ? ($lucro / $item->subtotal) * 100 : 0;

        return [
            $item->sale->created_at->format('d/m/Y H:i'),
            $item->sale->id,
            $item->product->name,
            $item->quantity,
            $item->unit_price,
            $custoUn,
            $item->subtotal,
            $custoTotal,
            $lucro,
            number_format($margem, 2).'%',
        ];
    }
}
