<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public function initialize()
    {
        $this->setSource('users');
    }
    // public static function checkRegistration($email, $password)
    // {
    //     $conditions ="email = :email: AND password = :password:";
    //     $parameters = array(
    //         'email' => $email,
    //         'password' => $password,
    //     );
    //     $user = Users::findFirst(array(
    //         $conditions,
    //         'bind' => $parameters
    //     ));
    //     return $user;
    // }
}

