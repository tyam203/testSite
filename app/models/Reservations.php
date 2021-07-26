<?php

use Phalcon\Mvc\Model;

class Reservations extends Model 
{
    public function initialize()
    {
        $this->setSource('reservations');
    }
}