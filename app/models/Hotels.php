<?php

use Phalcon\Mvc\Model;

class Hotels extends Model 
{
    public function initialize()
    {
        $this->setSource('hotels');
    }
    // public $id;
    // public $hotelName;
    // public $prefectureId;
    // public $prefectureName;
    // public $hotelImages;

    // }
}