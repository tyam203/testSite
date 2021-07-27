<?php

use Phalcon\Mvc\Controller;

class DetailController extends Controller
{
    public function indexAction()
    {
        $get = $this->request->get();
        $rooms = $this
        ->modelsManager
        ->createBuilder()
        ->columns(
            [
                'hotelName',
                'room_type',
                'capacity',
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
            )
        ->andwhere(
            'capacity >= :personCount:',
            )
        ->andwhere(
            'date = :checkInDate:',
            [
                'hotelId' => $get['hotelId'],
                'personCount' => $get['personCount'],
                'checkInDate' => $get['checkInDate'],
            ]
            )
        ->andwhere('Prices.stock >= 1')
        ->getQuery()
        ->execute();
            
        foreach($rooms as $room) {
            $hotelName = $room->hotelName;
            $hotelImages = $room->hotelImages;
        }
        $inCharge = date('Y年n月j日', strtotime($checkInDate . '-8 day')); 
        $this->view->setVars(
            [
                'hotelName' => $hotelName,
                'hotelImages' => $hotelImages,
                'hotelId' => $hotelId,
                'checkInDate' => $checkInDate,
                'stayCount' => $stayCount,
                'personCount'=> $personCount,
                'rooms' => $rooms,
                'inCharge' => $inCharge,
            ]
        );
    }

    public function confirmAction()
    {
        $get = $this->request->get();
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
            )
        ->andwhere(
            'capacity >= :personCount:',
        )
        ->andwhere(
            'date = :checkInDate:',
            [
                'roomId' => $get['roomId'],
                'personCount' => $get['personCount'],
                'checkInDate' => $get['checkInDate'],
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
        $this->view->setVars(
            [
                'hotelId' => $get['hotelId'],
                'roomId' => $get['roomId'],
                'checkInDate' => $get['checkInDate'],
                'personCount' => $get['personCount'],
                'stayCount' => $get['stayCount'],
                'prefectureId' => $prefectureId,
                'hotelName' => $hotelName,
                'roomType' => $roomType,
                'price' => $price,
            ]
        );
        $_SESSION['roomId'] = $get['roomId'];
        $_SESSION['checkInDate'] = $get['checkInDate'];
        $_SESSION['personCount'] = $get['personCount'];
        $_SESSION['stayCount'] = $get['stayCount'];
    }
} 