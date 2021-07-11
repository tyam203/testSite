<?php

use Phalcon\Mvc\Controller;

class SearchController extends Controller
{
    public function indexAction()
    {
        $prefectureId = $this->request->get('prefectureId');
        $personCount = $this->request->get('personCount');
        $checkInDate = $this->request->get('checkInDate');
        $stayCount = $this->request->get('stayCount');
        $checkOutDate = date('Y-m-d', strtotime($checkInDate . '+' . $stayCount . 'day'));
        $checkOutBefore = date('Y-m-d', strtotime($checkOutDate . '-1day'));


        // $builder = new Builder();
        // $hotel
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
            // ->addFrom(Rooms::class)
            // ->addFrom(Prices::class)
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
                    'prefectureId' => $prefectureId,
                ]
            )
            ->andwhere(
                'capacity >= :personCount:',
                [
                    'personCount' => $personCount,
                ]
            )
            // ->betweenwhere('Prices.date', $checkInDate, $checkOutBefore)
            ->andwhere('Prices.stock >= 1')
            ->groupBy('hotel_id')
            ->getQuery()
            ->execute();
         
        foreach($hotels as $hotel) {
            $prefecture = $hotel->prefectureName;
        }

        $this->view->prefecture = $prefecture;
        $this->view->hotels = $hotels;
        $this->view->checkInDate = $checkInDate;
        $this->view->stayCount = $stayCount;
        $this->view->personCount = $personCount;
    }
}

// $prefectureId = $this->request->get('prefectureId');

// $this->view->hotels = Hotels::find(
    //     [
        //         'columns' => [
            //             'hotelName',
            //         ],
            //         'conditions' => 'prefectureId = :prefectureId:',
            //         'bind' => [
                //             'prefectureId' => $prefectureId,
                //         ],
                //     ]
                //     );
                
                
                // public function indexAction()
                // {
                    //     $prefectureId = $this->request->get('prefectureId');
                    
                    //     $this->view->hotels = Hotels::find(
                        //         [
                            //             "prefectureId = '$prefectureId'",
                            //         ]
                            //         );
                            // }



// private function createSearchBuilder(){
//     $builder = new Builder();
//     $builder->column()

// }