<?php

use Phalcon\Mvc\Model;

class Prefectures extends Model 
{
  public function initialize()
  {
    $this->hasOne(
      'id',
      Hotels::class,
      'prefectureId',
    );
  }
}