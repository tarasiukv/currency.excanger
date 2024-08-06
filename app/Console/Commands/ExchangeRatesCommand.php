<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

class ExchangeRatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange-rates:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '1 per week get exchange currency';

    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        parent::__construct();
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->exchangeRateService->getExchangeRate();

        $this->info('Exchange rates updated successfully!');
    }
}
