<?php

use Phalcon\Mvc\Controller;

class ReservationController extends BaseController
{
  public function formAction()
  {
    $user = Users::findFirst($this->session->get('id'));
    $this->view->setVars(
        [
            'birthday' => $user->birthday,
            'gender' => $user->gender,
            'email' => $user->email,
            'tel' => $user->tel,
        ]
        );
  }

  public function confirmAction()
  {
    $post = $this->request->getPost();
    // var_dump($post);exit;
    $this->view->setVars([
            'roomId' => $this->session->get('roomId'),
            'checkInDate' => $this->session->get('checkInDate'),
            'stayCount' => $this->session->get('stayCount'),
            'personCount' => $this->session->get('personCount'),
            // 'price' => $post['price'],
            'checkOutDate' => date('Y-m-d', strtotime($this->session->get('checkInDate') . '+' . $this->session->get('stayCount') . 'day')),
        ]);

    $rooms = $this->modelsManager->createBuilder();
    $rooms->columns([
            'hotelName',
            'room_type',
            'price',
            'Prices.id as priceId',
            'hotelImages',
            'Rooms.id as roomId',
        ]);
    $rooms->from(Rooms::class);
    $rooms->join(
        Hotels::class,
        'Hotels.id = Rooms.hotel_id'
    );
    $rooms->join(
        Prices::class,
        'Prices.roomId = Rooms.id'
    );
    $rooms->where(
        'Rooms.id = :roomId:',
        [
            'roomId' => $this->session->get('roomId'),
        ]
        );
    $rooms->andwhere(
            'capacity >= :personCount:',
            [
                'personCount' => $this->session->get('personCount'),
            ]
            );
    $rooms->andwhere(
        'date = :checkInDate:',
        [
            'checkInDate' => $this->session->get('checkInDate'),
        ]
        );
    $selectedRoom = $rooms->getQuery()->execute();

    $selectedRoom->rewind();
    $this->view->room = $selectedRoom->current();
    // var_dump($selectedRoom->toArray());exit;
        
    // foreach($selectedRooms as $selectedRoom) {
    //     $hotelName = $room->hotelName;
    //     $roomName = $room->room_type;
    //     $priceId = $room->priceId;
    //     $price = $room->price;
    // }
    if ($post['gender'] == 1) {
        $formedGender = '男性';
    } else {
        $formedGender = '女性';
    }
    $this->view->setVars(
        [
            // 'hotelName' => $hotelName,
            // 'roomName' => $roomName,
            // 'priceId' => $priceId,
            // 'price' => $price,
            'name' => $post['name'],
            'birthday' => $post['birthday'],
            'gender' => $post['gender'],
            'formedGender' => $formedGender,
            'email' => $post['email'],
            'tel' => $post['tel'],
        ]
    ); 
  }

  public function completeAction()
  {
    if ($this->request->isPost()) {
      try {
          $post = $this->request->getPost();
          $this->db->begin();

          $hotel = Prices::findFirst([
              'conditions' => 'id = :priceId:',
              'bind' => [
                  'priceId' => $post['priceId'],
              ],
          ]);
          if ($hotel->stock > 0) {
              $hotel->stock -= 1;
              $result = $hotel->save();
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
          $this->session->remove('roomId');
          $this->session->remove('personCount');
          $this->session->remove('stayCount');
          $this->session->remove('checkInDate');
      } catch(Exception $ex) {
          $this->db->rollBack();
          $this->view->message = $ex->getMessage();
      }

  }
  }
}