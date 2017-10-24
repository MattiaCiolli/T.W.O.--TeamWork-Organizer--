<?php
/**
 * Created by PhpStorm.
 * User: mattia
 * Date: 18/10/17
 * Time: 11.08
 */

namespace App\Utility;


use App\Models\TransportOrder;

class TransportPriceCalculator extends PriceCalculator
{
    public function calculatePrice($order_in)
    {
        //initialize price
        $priceTemp=0;
        $i=0;
        $order_in=TransportOrder::find(1);
        //analyze products and update price
        foreach ($order_in->carrier as &$carr) {

            //carrier price
            $priceTemp=$priceTemp+config('carrier.carrierPrice');

            foreach ($carr->product as &$prod) {
                $i++;
                //product price
                $priceTemp=$priceTemp+$prod->description->price;
                //transport type
                $priceTemp=$priceTemp+config('carrier.carrierType.'.$prod->description->type);
            }
        }

        if($i>=5)//conf
        {
            $priceStrategyContext = new PriceStrategyContext('Q');
            $priceTemp= $priceStrategyContext->discount($priceTemp);
        }

        $pathLengthPrice = config('path.pricePerKm') * ($order_in->path->path_length/1000);
        $priceTemp=$priceTemp+$pathLengthPrice;

        if($order_in->path->path_length>5000)//conf
        {
            $priceStrategyContext = new PriceStrategyContext('P');
            $priceTemp= $priceStrategyContext->discount($priceTemp);
        }

        /*if(//summer)
        {
            $priceStrategyContext = new PriceStrategyContext('S');
            $priceTemp= $priceStrategyContext->discount($priceTemp);
        }

        if(//fidelity)
        {
            $priceStrategyContext = new PriceStrategyContext('F');
            $priceTemp= $priceStrategyContext->discount($priceTemp);
        }*/


//TEST----------------------------------------------------------------------------------
        /*       for($i=0; $i<4; $i++)
               {
                   $priceTemp=$priceTemp+config('carrier.carrierPrice');

                   for($j=0; $j<4; $j++)
                   {
                       //product price
                       $priceTemp=$priceTemp+1;
                       //transport type
                       $priceTemp=$priceTemp+config('carrier.carrierType.normal');
                   }

               }

               $pathLengthPrice = config('path.pricePerKm')*1;
               $priceTemp = $priceTemp+$pathLengthPrice;
               $priceStrategyContext = new PriceStrategyContext('Q');
               $priceTemp= $priceStrategyContext->discount($priceTemp);
       //FINE TEST----------------------------------------------------------------------------------
       */
        //set final price
        return round($priceTemp, 2);
    }
}