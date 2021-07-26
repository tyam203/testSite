<?php

use Phalcon\Mvc\Model;

class Rooms extends Model 
{
    public function initialize()
    {
        $this->setSource('rooms');
    }
}