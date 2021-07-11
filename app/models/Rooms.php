<?php

use Phalcon\Mvc\Model;

class Rooms extends Model 
{
    public $id;
    public $hotel_id;
    public $room_type;
    public $capacity;

}