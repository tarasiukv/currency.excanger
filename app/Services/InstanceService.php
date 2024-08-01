<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class InstanceService
{
    private static $instance = null;
    private $apiKey;

    private function __construct()
    {
        $this->apiKey = env('EXCHANGE_RATE_API_KEY');
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new InstanceService();
        }

        return self::$instance;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }
}
