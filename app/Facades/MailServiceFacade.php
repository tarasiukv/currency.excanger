<?php
namespace App\Facades;

use App\Services\MailService;
use Illuminate\Support\Facades\Facade;

class MailServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MailService::class;
    }
}
