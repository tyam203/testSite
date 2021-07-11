<?php

use Phalcon\Mvc\Controller;

class DetailController extends Controller
{
    public function indexAction()
    {
        $hotelId = $this->request->get('hotelId');
        $checkInDate = $this->request->get('checkInDate');
        $rooms = $this
        ->modelsManager
        ->createBuilder()
        ->columns(
            [
                'hotelName',
                'room_type',
                'price',
                'hotelImages',

            ]
        )
        ->from(Rooms::class)
        ->join(
            Hotels::class,
            'Hotels.id = Rooms.hotel_id'
        )
        ->join(
            Prices::class,
            'Prices.roomId = Rooms.id'
        )
        ->where(
            'hotel_id = :hotelId:',
            [
                'hotelId' => $hotelId,
            ]
        )
        ->andwhere(
            'date = :checkInDate:',
            [
                'checkInDate' => $checkInDate,
            ]
        )
        ->getQuery()
        ->execute();

        foreach($rooms as $room) {
            $hotelName = $room->hotelName;
            $hotelImages = $room->hotelImages;
        }
        $this->view->hotelName = $hotelName;
        $this->view->hotelImages = $hotelImages;
        $this->view->rooms = $rooms;

    }
} 