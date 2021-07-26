<?php

use Phalcon\Mvc\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {
        $get = $this->request->get();
        $prefecture = Hotels::findFirst([
            'conditions' => 'prefectureId = :prefectureId:',
            'bind' => [
                'prefectureId' => $get['prefectureId'],
            ],
        ]);
        
        $hotels = $this
            ->modelsManager
            ->createBuilder()
            ->columns(
                [
                    'hotelName',
                    'price',
                    'date',
                    'hotel_id',
                    'hotelImages',
                    'prefectureName',
                ]
            )
            ->from(Hotels::class)
            ->join(
                Rooms::class,
                'Hotels.id = Rooms.hotel_id'
            )
            ->join(
                Prices::class,
                'Prices.roomId = Rooms.id'
            )
            ->where(
                'prefectureId = :prefectureId:',
                [
                    'prefectureId' => $get['prefectureId'],
                ]
            )
            ->andwhere(
                'capacity >= :personCount:',
                [
                    'personCount' => $get['personCount'],
                ]
            )
            ->andwhere(
                'date = :checkInDate:',
                [
                    'checkInDate' => $get['checkInDate'],
                ]
            )
            ->andwhere('Prices.stock >= 1')
            ->groupBy('hotel_id')
            ->getQuery()
            ->execute();

            foreach($hotels as $hotel) {
                $this->view->hotel = $hotel;
            }
            $this->view->setVars(
                [
                    'hotels' => $hotels,
                    'checkInDate' => $get['checkInDate'],
                    'stayCount' => $get['stayCount'],
                    'personCount' => $get['personCount'],
                    'prefecture' => $prefecture->prefectureName,
                ]
            );
    }
}