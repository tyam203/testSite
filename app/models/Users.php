<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $id;
    public $name;
    public $birthday;
    public $gender;
    public $email;
    public $tel;
    public $password;
}