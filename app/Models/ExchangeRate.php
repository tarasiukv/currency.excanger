<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency_id',
        'to_currency_id',
        'rate',
    ];

    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    /**
     * Scope a query to search exchange rates by from and to currency IDs.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $fromCurrencyId
     * @param int $toCurrencyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByCurrencies($query, $fromCurrencyId, $toCurrencyId)
    {
        return $query->where('from_currency_id', $fromCurrencyId)
            ->where('to_currency_id', $toCurrencyId);
    }
}
