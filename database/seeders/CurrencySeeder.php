<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'symbol' => '$',
                'name' => 'US Dollar',
                'symbol_native' => '$',
                'code' => 'USD',
                'name_plural' => 'US dollars'
            ], [
                'symbol' => 'CA$',
                'name' => 'Canadian Dollar',
                'symbol_native' => '$',
                'code' => 'CAD',
                'name_plural' => 'Canadian dollars'
            ], [
                'symbol' => '€',
                'name' => 'Euro',
                'symbol_native' => '€',
                'code' => 'EUR',
                'name_plural' => 'euros'
            ], [
                'symbol' => 'AED',
                'name' => 'United Arab Emirates Dirham',
                'symbol_native' => 'د.إ.‏',
                'code' => 'AED',
                'name_plural' => 'UAE dirhams'
            ], [
                'symbol' => 'AU$',
                'name' => 'Australian Dollar',
                'symbol_native' => '$',
                'code' => 'AUD',
                'name_plural' => 'Australian dollars'
            ], [
                'symbol' => 'BGN',
                'name' => 'Bulgarian Lev',
                'symbol_native' => 'лв.',
                'code' => 'BGN',
                'name_plural' => 'Bulgarian leva'
            ], [
                'symbol' => 'BYR',
                'name' => 'Belarusian Ruble',
                'symbol_native' => 'BYR',
                'code' => 'BYR',
                'name_plural' => 'Belarusian rubles'
            ], [
                'symbol' => 'CHF',
                'name' => 'Swiss Franc',
                'symbol_native' => 'CHF',
                'code' => 'CHF',
                'name_plural' => 'Swiss francs'
            ], [
                'symbol' => 'CN¥',
                'name' => 'Chinese Yuan',
                'symbol_native' => 'CN¥',
                'code' => 'CNY',
                'name_plural' => 'Chinese yuan'
            ], [
                'symbol' => 'Kč',
                'name' => 'Czech Republic Koruna',
                'symbol_native' => 'Kč',
                'code' => 'CZK',
                'name_plural' => 'Czech Republic korunas'
            ], [
                'symbol' => 'Dkr',
                'name' => 'Danish Krone',
                'symbol_native' => 'kr',
                'code' => 'DKK',
                'name_plural' => 'Danish kroner'
            ], [
                'symbol' => 'Ekr',
                'name' => 'Estonian Kroon',
                'symbol_native' => 'kr',
                'code' => 'EEK',
                'name_plural' => 'Estonian kroons'
            ], [
                'symbol' => 'EGP',
                'name' => 'Egyptian Pound',
                'symbol_native' => 'ج.م.‏',
                'code' => 'EGP',
                'name_plural' => 'Egyptian pounds'
            ], [
                'symbol' => '£',
                'name' => 'British Pound Sterling',
                'symbol_native' => '£',
                'code' => 'GBP',
                'name_plural' => 'British pounds sterling'
            ], [
                'symbol' => 'GEL',
                'name' => 'Georgian Lari',
                'symbol_native' => 'GEL',
                'code' => 'GEL',
                'name_plural' => 'Georgian laris'
            ], [
                'symbol' => 'HK$',
                'name' => 'Hong Kong Dollar',
                'symbol_native' => '$',
                'code' => 'HKD',
                'name_plural' => 'Hong Kong dollars'
            ], [
                'symbol' => 'Ft',
                'name' => 'Hungarian Forint',
                'symbol_native' => 'Ft',
                'code' => 'HUF',
                'name_plural' => 'Hungarian forints'
            ], [
                'symbol' => '¥',
                'name' => 'Japanese Yen',
                'symbol_native' => '￥',
                'code' => 'JPY',
                'name_plural' => 'Japanese yen'
            ], [
                'symbol' => '₩',
                'name' => 'South Korean Won',
                'symbol_native' => '₩',
                'code' => 'KRW',
                'name_plural' => 'South Korean won'
            ], [
                'symbol' => 'KZT',
                'name' => 'Kazakhstani Tenge',
                'symbol_native' => 'тңг.',
                'code' => 'KZT',
                'name_plural' => 'Kazakhstani tenges'
            ], [
                'symbol' => 'Lt',
                'name' => 'Lithuanian Litas',
                'symbol_native' => 'Lt',
                'code' => 'LTL',
                'name_plural' => 'Lithuanian litai'
            ], [
                'symbol' => 'Ls',
                'name' => 'Latvian Lats',
                'symbol_native' => 'Ls',
                'code' => 'LVL',
                'name_plural' => 'Latvian lati'
            ], [
                'symbol' => 'MDL',
                'name' => 'Moldovan Leu',
                'symbol_native' => 'MDL',
                'code' => 'MDL',
                'name_plural' => 'Moldovan lei'
            ], [
                'symbol' => 'MX$',
                'name' => 'Mexican Peso',
                'symbol_native' => '$',
                'code' => 'MXN',
                'name_plural' => 'Mexican pesos'
            ], [
                'symbol' => 'Nkr',
                'name' => 'Norwegian Krone',
                'symbol_native' => 'kr',
                'code' => 'NOK',
                'name_plural' => 'Norwegian kroner'
            ], [
                'symbol' => 'NZ$',
                'name' => 'New Zealand Dollar',
                'symbol_native' => '$',
                'code' => 'NZD',
                'name_plural' => 'New Zealand dollars'
            ], [
                'symbol' => 'zł',
                'name' => 'Polish Zloty',
                'symbol_native' => 'zł',
                'code' => 'PLN',
                'name_plural' => 'Polish zlotys'
            ], [
                'symbol' => 'RON',
                'name' => 'Romanian Leu',
                'symbol_native' => 'RON',
                'code' => 'RON',
                'name_plural' => 'Romanian lei'
            ], [
                'symbol' => 'Skr',
                'name' => 'Swedish Krona',
                'symbol_native' => 'kr',
                'code' => 'SEK',
                'name_plural' => 'Swedish kronor'
            ], [
                'symbol' => 'S$',
                'name' => 'Singapore Dollar',
                'symbol_native' => '$',
                'code' => 'SGD',
                'name_plural' => 'Singapore dollars'
            ], [
                'symbol' => 'TL',
                'name' => 'Turkish Lira',
                'symbol_native' => 'TL',
                'code' => 'TRY',
                'name_plural' => 'Turkish Lira'
            ], [
                'symbol' => '₴',
                'name' => 'Ukrainian Hryvnia',
                'symbol_native' => '₴',
                'code' => 'UAH',
                'name_plural' => 'Ukrainian hryvnias'
            ],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
