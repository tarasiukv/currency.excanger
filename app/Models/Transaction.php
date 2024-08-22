<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_currency_id',
        'to_currency_id',
        'amount',
        'rate',
        'result_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromCurrency()
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    public function toCurrency()
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    public function scopeFilterByUser($query, $user_id)
    {
        if (!empty($user_id)) {
            if (is_array($user_id)) {
                $query->whereIn('user_id', $user_id);
            } else {
                $query->where('user_id', $user_id);
            }
        }
        return $query;
    }

    public function scopeFilterByFromCurrency($query, $from_currency_id)
    {
        if (!empty($from_currency_id)) {
            if (is_array($from_currency_id)) {
                $query->whereIn('from_currency_id', $from_currency_id);
            } else {
                $query->where('from_currency_id', $from_currency_id);
            }
        }
        return $query;
    }

    public function scopeFilterByToCurrency($query, $to_currency_id)
    {
        if (!empty($to_currency_id)) {
            if (is_array($to_currency_id)) {
                $query->whereIn('to_currency_id', $to_currency_id);
            } else {
                $query->where('to_currency_id', $to_currency_id);
            }
        }
        return $query;
    }

    public function scopeFilterByDate($query, $from_date = null, $to_date = null)
    {
        if (!empty($from_date)) {
            $query->where('created_at', '>=', $from_date);
        }
        if (!empty($to_date)) {
            $query->where('created_at', '<=', $to_date);
        }
        return $query;
    }
}
