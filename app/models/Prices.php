<?php

use Phalcon\Mvc\Model;

class Prices extends Model 
{
    public function initialize()
    {
        $this->belongsTo(
            'roomId',
            Rooms::class,
            'id',
        );
    }

}