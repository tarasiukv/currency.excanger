<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'symbol_native',
        'code',
        'name_plural',
    ];

    public function exchangeRatesFrom()
    {
        return $this->hasMany(ExchangeRate::class, 'from_currency_id');
    }

    public function exchangeRatesTo()
    {
        return $this->hasMany(ExchangeRate::class, 'to_currency_id');
    }

    public function transactionsFrom()
    {
        return $this->hasMany(Transaction::class, 'from_currency_id');
    }

    public function transactionsTo()
    {
        return $this->hasMany(Transaction::class, 'to_currency_id');
    }
}
