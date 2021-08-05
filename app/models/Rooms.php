<?php

use Phalcon\Mvc\Model;

class Rooms extends Model 
{
    public function initialize()
    {
        $this->belongsTo(
            'hotel_id',
            Hotels::class,
            'id',
        );
        $this->hasMany(
            'roomId',
            Prices::class,
            'id',
        );
        // $this->hasMany(
        //     'id',
        //     Reservation::class,
        //     'roomId',
        // );

    }
}