<?php
/**
 * Created by PhpStorm.
 * User: Davide
 * Date: 13/10/2017
 * Time: 14:22
 */

namespace App\Services;


use App\Models\Carrier;


class CarrierService
{

    public function handleProduct($productList){

        usort($productList, array($this, "cmp"));

        $numberOfProduct = count($productList);


        $slotCarrier = config('carrier.carrier.defaultSize');
        $indexCarrier=0;
        $indexProduct=0;
        while ($indexProduct<$numberOfProduct){


            $carrier[$indexCarrier] = new Carrier();
            $carrier[$indexCarrier]->free_slots=$slotCarrier;
            $carrier[$indexCarrier]->save();

            $freeSlot=$carrier[$indexCarrier]->free_slots;

            $sizeOfProduct = $productList[$indexProduct]->description->size;
            $slotOfProduct = config('carrier.packet.defaultSize.'.$sizeOfProduct);
            for($indexProduct; $slotOfProduct <= $freeSlot ;$indexProduct++){

                $sizeOfProduct = $productList[$indexProduct]->description->size;
                $slotOfProduct = config('carrier.packet.defaultSize.'.$sizeOfProduct);

                $productList[$indexProduct]->setCarrier($carrier[$indexCarrier]);
                $productList[$indexProduct]->save();

                $freeSlot = $freeSlot - $slotOfProduct;
                $carrier[$indexCarrier]->setFreeSlots($freeSlot);

            }
            $indexCarrier++;
        }
        return $carrier;
    }

    public  function cmp($a, $b)
    {
        return -1*(strcmp($a->description->size, $b->description->size));
    }

}