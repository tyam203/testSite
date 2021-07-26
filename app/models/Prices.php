<?php

use Phalcon\Mvc\Model;

class Prices extends Model 
{
    public function initialize()
    {
        $this->setSource('prices');
    }

}