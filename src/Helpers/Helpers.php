<?php
namespace Yahyya\Helpers;

class Helpers
{
    static function convertToEur($val,$currency): float|int
    {
        $currencies = file_get_contents('https://developers.paysera.com/tasks/api/currency-exchange-rates');
        $currencies = json_decode($currencies);
        foreach($currencies->rates as $cur=>$rate) {
            if ($cur == $currency) {
                return $val * $rate;
            }
        }
    }

}