<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Resource extends Model
{
    private $id;
    private $diaryId;
    private $maxFreeConsSlots;

    public function slots()
    {
        return $this->hasMany('App\Models\Slot');
    }

    public function setSlots($slots)
    {
        $this->slots()->associate($slots);
    }
    public function isFree($slotIndex)
    {
        $idDrone = 0; //add qualcosa
        return $idDrone;
    }

    public function reserve($idResource, $startIndexSlot, $journeySlot){

    }
}
