<?php

use Phalcon\Mvc\Model;

class Prices extends Model 
{
    public $id;
    public $roomId;
    public $date;
    public $price;
    public $stock;

}