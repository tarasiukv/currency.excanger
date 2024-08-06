<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExchangeRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_currency_id' => $this->from_currency_id,
            'from_currency' => new CurrencyResource($this->whenLoaded('from_currency')),
            'to_currency_id' => $this->to_currency_id,
            'to_currency' => new CurrencyResource($this->whenLoaded('to_currency')),
            'rate' => $this->rate,
            'created_at' => $this->created_at ? $this->created_at->format('d.m.Y, H:i') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d.m.Y, H:i') : null,
        ];
    }
}
