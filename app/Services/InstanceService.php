<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class InstanceService
{

    private static $instance = null;
    private $minfin_api_url;
    private $erapi_api_url;

    private function __construct()
    {
        $this->minfin_api_url = env('MINFIN_EXCHANGE_RATE_API_URL');
        $this->erapi_api_url = env('ERAPI_EXCHANGE_RATE_API_URL');
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new InstanceService();
        }

        return self::$instance;
    }

    public function getApiUrls()
    {
        return [
            'minfin_api_url' => $this->minfin_api_url,
            'erapi_api_url' => $this->erapi_api_url,
        ];
    }
}
