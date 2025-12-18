<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batch extends Model
{
    protected $fillable = ['product_id', 'batch_code', 'quantity', 'cost_price', 'expiration_date'];

    protected $casts = [
        'expiration_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where('quantity', '>', 0)
            ->where('expiration_date', '>=', now());
    }
}
