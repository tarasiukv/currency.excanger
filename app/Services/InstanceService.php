<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class InstanceService
{

    private static $instance = null;
    private $minfin_api_ey;
    private $erapi_api_ey;

    private function __construct()
    {
        $this->minfin_api_ey = env('MINFIN_EXCHANGE_RATE_API_KEY');
        $this->erapi_api_ey = env('ERAPI_EXCHANGE_RATE_API_KEY');
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
        return [
            'minfin' => $this->minfin_api_ey,
            'erapi' => $this->erapi_api_ey,
        ];
    }
}
