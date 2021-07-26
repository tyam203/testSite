<?php

use Phalcon\Mvc\Controller;

class FormController extends Controller
{
    public function indexAction(){
        $user = Users::findFirst($_SESSION['id']);
            $this->view->setVars(
                [
                    'birthday' => $user->birthday,
                    'gender' => $user->gender,
                    'email' => $user->email,
                    'tel' => $user->tel,
                ]
                );
    }
    public function confirmAction(){
        $post = $this->request->getPost();
        $this->view->setVars(
            [
                'roomId' => $_SESSION['roomId'],
                'checkInDate' => $_SESSION['checkInDate'],
                'stayCount' => $_SESSION['stayCount'],
                'personCount' => $_SESSION['personCount'],
                'price' => $post['price'],
                'checkOutDate' => date('Y-m-d', strtotime($_SESSION['checkInDate'] . '+' . $_SESSION['stayCount'] . 'day')),
            ]
            );

        $rooms = $this
        ->modelsManager
        ->createBuilder()
        ->columns(
            [
                'hotelName',
                'room_type',
                'price',
                'Prices.id as priceId',
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
            'Rooms.id = :roomId:',
            [
                'roomId' => $_SESSION['roomId'],
            ]
            )
        ->andwhere(
                'capacity >= :personCount:',
                [
                    'personCount' => $_SESSION['personCount'],
                ]
            )
        ->andwhere(
            'date = :checkInDate:',
            [
                'checkInDate' => $_SESSION['checkInDate'],
            ]
            )
        ->getQuery()
        ->execute();
            
        foreach($rooms as $room) {
            $hotelName = $room->hotelName;
            $roomName = $room->room_type;
            $priceId = $room->priceId;
            $price = $room->price;
        }
        if ($post['gender'] == 1) {
            $formedGender = '男性';
        } else {
            $formedGender = '女性';
        }
        $this->view->setVars(
            [
                'hotelName' => $hotelName,
                'roomName' => $roomName,
                'priceId' => $priceId,
                'price' => $price,
                'name' => $post['name'],
                'birthday' => $post['birthday'],
                'gender' => $post['gender'],
                'formedGender' => $formedGender,
                'email' => $post['email'],
                'tel' => $post['tel'],
            ]
        ); 
    }
}