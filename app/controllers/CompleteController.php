<?php

use Phalcon\Mvc\Controller;

class CompleteController extends Controller
{
    public function indexAction()
    {  
        date_default_timezone_set('Asia/Tokyo');
        $reservation = new Reservations();
        $reservation->assign(
            [
            'id' => null,
            'checkInDate' => $_POST['checkInDate'],
            'checkOutDate' => $_POST['checkOutDate'],
            'personCount' => $_POST['personCount'],
            'price' => $_POST['price'],
            'roomId' => $_POST['roomId'],
            'userId' => $_POST['userId'],
            'name' => $_POST['name'],
            'gender' => $_POST['gender'],
            'birthday' => $_POST['birthday'],
            'email' => $_POST['email'],
            'phoneNumber' => $_POST['tel'],
            'status' => 'reserved',
            'bookingDate'=> date('Y-m-d H:i:s'),
            ]
        );
        
        $success = $reservation->save();
        
        $this->view->success = $success;
        
        if($success) {
            $message = '予約が完了しました';
        } else {
            $message = 'Sorry, the following problems were generated:<br>'
            . implode('<br>', $reservation->getMessages());
        }
        
        $this->view->message = $message;

        $phql = "
            UPDATE Prices
            SET
                stock = stock - 1
            WHERE
                id = :priceId:";

        $records  = $this
            ->modelsManager
            ->executeQuery(
                $phql,
                [
                    'priceId' => $_POST['priceId'],
                ]
                )
        ;

        // $price = Prices::find(
        //     [
        //         "roomId = 1",
        //         "date = 2021-06-01",
        //     ]
        //     );
        // $price = Prices::find("id = $_POST['priceId']");

        // $price->stock = 15;
        // $price->update();
    }
    
}
// null,
// $this->request->getPost(),
// [
//     'checkInDate',
//     'checkOutDate',
//     'personCount',
//     'price',
//     'roomId',
//     'userId',
//     'name',
//     'gender',
//     'birthday',
//     'email',
//     'tel',
//     'password'
// ],
// 'reserved',
// date('Y-m-d H:i:s'),