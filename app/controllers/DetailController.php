<?php

use Phalcon\Mvc\Controller;

class DetailController extends Controller
{
    public function indexAction()
    {
        $hotelId = $this->request->get('hotelId');
        $checkInDate = $this->request->get('checkInDate');
        $personCount = $this->request->get('personCount');
        $rooms = $this
        ->modelsManager
        ->createBuilder()
        ->columns(
            [
                'hotelName',
                'room_type',
                'price',
                'hotelImages',
                'Rooms.id as roomId'
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
                'capacity >= :personCount:',
                [
                    'personCount' => $personCount,
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

    public function confirmAction()
    {
        $roomId = $this->request->get('roomId');
        $hotelId = $this->request->get('hotelId');
        $checkInDate = $this->request->get('checkInDate');  
        $personCount = $this->request->get('personCount');  
        $stayCount = $this->request->get('stayCount');  
        $rooms = $this
        ->modelsManager
        ->createBuilder()
        ->columns(
            [
                'hotelName',
                'room_type',
                'price',
                'prefectureId',
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
            'Rooms.id = :roomId:',
            [
                'roomId' => $roomId,
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
            $roomType = $room->room_type;
            $price = $room->price;
            $prefectureId = $room->prefectureId;
        }
        $this->view->hotelId = $hotelId;
        $this->view->roomId = $roomId;
        $this->view->prefectureId = $prefectureId;
        $this->view->hotelName = $hotelName;
        $this->view->roomType = $roomType;
        $this->view->price = $price;
        $this->view->checkInDate = $checkInDate; 
        $this->view->personCount = $personCount; 
        $this->view->stayCount = $stayCount; 
    }
} 