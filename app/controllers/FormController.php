<?php

use Phalcon\Mvc\Controller;

class FormController extends Controller
{
    public function indexAction(){
        $this->view->hotelId = $this->request->getPost('hotelId');
        $this->view->roomId = $this->request->getPost('roomId');
        $this->view->checkInDate = $this->request->getPost('checkInDate');
        $this->view->stayCount = $this->request->getPost('stayCount');
        $this->view->personCount = $this->request->getPost('personCount');
        $this->view->price = $this->request->getPost('price');
        $id = $_SESSION['id'];
        $users = Users::find(
            [
                "id = '$id'",
                // "id = 15",
            ]
            );
        $this->view->users = $users;
        foreach($users as $user) {
            $_SESSION['birthday'] = $user->birthday;
            $_SESSION['gender'] = $user->gender;
            $_SESSION['email'] = $user->email;
            $_SESSION['tel'] = $user->tel;
        }
    }
    public function confirmAction(){
        $this->view->hotelId = $this->request->getPost('hotelId');
        $this->view->roomId = $this->request->getPost('roomId');
        $this->view->checkInDate = $this->request->getPost('checkInDate');
        $this->view->stayCount = $this->request->getPost('stayCount');
        $this->view->personCount = $this->request->getPost('personCount');
        $this->view->price = $this->request->getPost('price');
        $this->view->checkOutDate = date('Y-m-d', strtotime($this->request->getPost('checkInDate') . '+' . $this->request->getPost('stayCount') . 'day'));

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
                'roomId' => $this->request->getPost('roomId'),
                // 'hotelId' => $hotelId,
            ]
            )
        ->andwhere(
                'capacity >= :personCount:',
                [
                    'personCount' => $this->request->getPost('personCount'),
                ]
            )
        ->andwhere(
            'date = :checkInDate:',
            [
                'checkInDate' => $this->request->getPost('checkInDate'),
                // 'checkInDate' => $this->request->getPost('checkInDate'),
            ]
            )
        ->getQuery()
        ->execute();
            
        foreach($rooms as $room) {
            $hotelName = $room->hotelName;
            $roomName = $room->room_type;
            $hotelImages = $room->hotelImages;
            $priceId = $room->priceId;
        }
        $this->view->hotelName = $hotelName;
        $this->view->roomName = $roomName;
        $this->view->hotelImages = $hotelImages;
        $this->view->priceId = $priceId;
        $this->view->name = $this->request->getPost('name');    
        $this->view->birthday = $this->request->getPost('birthday');    
        $this->view->gender = $this->request->getPost('gender');    
        $this->view->email = $this->request->getPost('email');    
        $this->view->tel = $this->request->getPost('tel');    
    }
}