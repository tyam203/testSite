<?php

use Phalcon\Mvc\Controller;

class SearchController extends BaseController
{

    public function indexAction() {
        if(isset($this->session->id)) {
            $name = $this->session->name;
            $link = $this->tag->linkTo(["customer/logout", "ログアウト", 'class' => 'btn btn-primary']); 
        } else{
            $name = 'ゲスト';
            $link = $this->tag->linkTo(["customer/login", "ログイン", 'class' => 'btn btn-primary']); 
        }
        $this->view->setVars(
            [
                'link' => $link,
                'name' => $name,
            ]
            );
        
        $this->view->prefectures = Prefectures::find();

        function makeOptions($min, $max, $unit) {
            for($i = $min; $i <= $max; $i++) {
                echo '<option value="' . $i . '">' . $i . $unit . '</option>';
            }   
        }
    }


    public function resultAction()
    {
        $get = $this->request->get();
        $prefecture = Prefectures::findFirst([
            'conditions' => 'id = :prefectureId:',
            'bind' => [
                'prefectureId' => $get['prefectureId'],
            ],
        ]);

        $searchedHotels = $this->modelsManager->createBuilder();
        $searchedHotels->columns([
                    'hotelName',
                    'price',
                    'date',
                    'hotel_id',
                    'hotelImages',
                    'prefectureName',
        ]);
        $searchedHotels->from(Hotels::class);
        $searchedHotels->join(
                Rooms::class,
                'Hotels.id = Rooms.hotel_id'
        );
        $searchedHotels->join(
                Prefectures::class,
                'Hotels.prefectureId = Prefectures.id'
        );
        $searchedHotels->join(
                Prices::class,
                'Prices.roomId = Rooms.id'
        );
        $searchedHotels->where(
                'prefectureId = :prefectureId:',
                [
                    'prefectureId' => $get['prefectureId'],
                ]
                );
        $searchedHotels->andwhere(
                'capacity >= :personCount:',
                [
                    'personCount' => $get['personCount'],
                ]
                );
        $searchedHotels->andwhere(
                'date = :checkInDate:',
                [
                    'checkInDate' => $get['checkInDate'],
                ]
                );
        $searchedHotels->andwhere('Prices.stock >= 1');
        $searchedHotels->groupBy('hotel_id');
        $hotels = $searchedHotels->getQuery()->execute();

        $this->view->setVars([
                'hotels' => $hotels,
                'checkInDate' => $get['checkInDate'],
                'stayCount' => $get['stayCount'],
                'personCount' => $get['personCount'],
                'prefecture' => $prefecture->prefectureName,
            ]);
    }

    public function hotelPlanAction()
    {
        $get = $this->request->get();
        $searchedRooms = $this->modelsManager->createBuilder();
        $searchedRooms->columns([
                'hotelName',
                'room_type',
                'capacity',
                'price',
                'hotelImages',
                'Rooms.id as roomId'
            ]);
        $searchedRooms->from(Rooms::class);
        $searchedRooms->join(
            Hotels::class,
            'Hotels.id = Rooms.hotel_id'
        );
        $searchedRooms->join(
            Prices::class,
            'Prices.roomId = Rooms.id'
        );
        $searchedRooms->where(
            'hotel_id = :hotelId:',
        );
        $searchedRooms->andwhere(
            'capacity >= :personCount:',
        );
        $searchedRooms->andwhere(
            'date = :checkInDate:',
            [
                'hotelId' => $get['hotelId'],
                'personCount' => $get['personCount'],
                'checkInDate' => $get['checkInDate'],
            ]);
        $searchedRooms->andwhere('Prices.stock >= 1');
        $rooms = $searchedRooms->getQuery()->execute();
            
        $rooms->rewind();
        $this->view->room = $rooms->current();

        $inCharge = date('Y年n月j日', strtotime($checkInDate . '-8 day')); 
        $this->view->setVars(
            [
                'hotelName' => $hotelName,
                'hotelImages' => $hotelImages,
                'hotelId' => $hotelId,
                'checkInDate' => $checkInDate,
                'stayCount' => $stayCount,
                'personCount'=> $get['personCount'],
                'rooms' => $rooms,
                'inCharge' => $inCharge,
            ]
        );
    }

    public function confirmAction()
    {
        $get = $this->request->get();
        $selectedRooms = $this->modelsManager->createBuilder();
        $selectedRooms->columns([
                'hotelName',
                'room_type',
                'price',
                'prefectureId',
            ]);
        $selectedRooms->from(Rooms::class);
        $selectedRooms->join(
            Hotels::class,
            'Hotels.id = Rooms.hotel_id'
        );
        $selectedRooms->join(
            Prices::class,
            'Prices.roomId = Rooms.id'
        );
        $selectedRooms->where(
            'Rooms.id = :roomId:',
        );
        $selectedRooms->andwhere(
            'date = :checkInDate:',
            [
                'roomId' => $get['roomId'],
                'checkInDate' => $get['checkInDate'],
            ]
            );
        $rooms = $selectedRooms->getQuery()->execute();
            
        $rooms->rewind();
        $this->view->room = $rooms->current();
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
        $this->session->set('reservation', 'ongoing');
        $this->session->set('roomId', $get['roomId']);
        $this->session->set('checkInDate', $get['checkInDate']);
        $this->session->set('personCount', $get['personCount']);
        $this->session->set('stayCount', $get['stayCount']);
    }
}