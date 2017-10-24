<?php
/**
 * Created by PhpStorm.
 * User: mattia
 * Date: 18/10/17
 * Time: 10.54
 */

namespace App\Services;

use App\Models\Price;
use App\Models\Currency;
use App\Models\TransportOrder;
use App\Utility\PriceContext;
use App\Utility\TransportPriceCalculator;

class PriceService
{
    public function CalculateTransportPrice($order_in)
    {
        $priceContext = new PriceContext(null, new TransportPriceCalculator());
        $priceValue=$priceContext->preventive();
        //read currency
        $currencyConf = config('currency.currency.defaultCurrency');
        $curr = Currency::where('currency_symbol', $currencyConf)->first();
        $price = new Price();
        $price->setCurrency($curr);
        $price->setValue($priceValue);
        $price->save();
        $order_in=TransportOrder::find(1);
        $order_in->setPrice($price);
        $order_in->save();

        return $price;
    }
}