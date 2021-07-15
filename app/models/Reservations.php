<?php

use Phalcon\Mvc\Model;

class Reservations extends Model 
{
    public $id;
    public $checkInDate;
    public $checkOutDate;
    public $personCount;
    public $price;
    public $roomId;
    public $userId;
    public $name;
    public $gender;
    public $birthday;
    public $email;
    public $phoneNumber;
    public $status;
    public $bookingDate;

}