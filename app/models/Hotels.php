<?php

use Phalcon\Mvc\Model;

class Hotels extends Model 
{
  public function initialize()
  {
    $this->hasMany(
      'id',
      Rooms::class,
      'hotel_id',
      [
        'reusable' => true,
        'alias'    => 'Rooms'
      ]
    );
    $this->hasOne(
      'prefectureId',
      Prefectures::class,
      'id',
      [
        'reusable' => true,
        'alias'    => 'Prefectures'
      ]
    );
  }
}