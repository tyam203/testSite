<?php

use Phalcon\Mvc\Controller;

class CompleteController extends Controller
{
    public function indexAction()
    {  
        if($this->request->isPost()){
            try{
                $post = $this->request->getPost();
                $this->db->begin();

                $price = Prices::findFirst(
                [
                      'conditions' => 'id = :priceId:',
                      'bind' => [
                          'priceId' => $post['priceId'],
                    ]
                  ]
                );
                if($price->stock > 0) {
                    $price->stock -= 1;
                    $result = $price->save();
                } else {
                    throw new Exception('予約手続き中に空室がなくなりました');
                }
                if (false === $result) {
                    throw new Exception('予約に失敗しました');
                }
                
                $reservation = new Reservations();
                $reservation->assign(
                    [
                        'checkInDate' => $post['checkInDate'],
                        'checkOutDate' => $post['checkOutDate'],
                        'personCount' => $post['personCount'],
                        'price' => $post['price'],
                        'roomId' => $post['roomId'],
                        'userId' => $post['userId'],
                        'name' => $post['name'],
                        'gender' => $post['gender'],
                        'birthday' => $post['birthday'],
                        'email' => $post['email'],
                        'phoneNumber' => $post['tel'],
                        'status' => 'reserved',
                        'bookingDate'=> date('Y-m-d H:i:s'),
                    ]
                );        
                $result = $reservation->save();
                if (false === $result) {
                    throw new Exception('予約に失敗しました');
                }

                $success = $this->db->commit();
                $this->view->message = '予約が完了しました';
                unset($_SESSION['roomId'], $_SESSION['personCount'], $_SESSION['stayCount'], $_SESSION['checkInDate']);
            } catch(Exception $ex) {
                $this->db->rollBack();
                $this->view->message = $ex->getMessage();
            }

        }
    }
    
}