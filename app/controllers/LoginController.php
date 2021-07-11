<?php
use Phalcon\Mvc\Controller;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Session\Adapter\Files as Session;

// $session = new Manager();
// $files = new Stream(
//     [
//         'savePath' => 'N;MODE;/path',
//         // 'savePath' => '/tmp',
//     ]
// );
// $session->setAdapter($files);
// $session->start();

class LoginController extends Controller
{
    

    public function indexAction()
    {
        
    }

    public function checkAction() {
                if ($this->request->isPost()) {
                    $email = $this->request->getPost('email');
                    $password = $this->request->getPost('password');
                    $users = Users::find(
                    // $this->view->users = Users::find(
                        [
                            "email = '$email'",
                            "password = '$password'",
                        ]
                        );
                    $this->view->users = $users;
                    foreach($users as $user) {
                        $this->session->set('name', $user->name);
                    }
                        // $container->setShared(
                        //     'session',
                        //     function () {
                        //         $session = new Session();
                        
                        //         $session->start();
                        
                        //         return $session;
                        //     }
                        // );

                        // $session = new Manager();
                        // $files = new Stream(
                        //     [
                        //         'savePath' => '/tmp',
                        //     ]
                        // );
                        // $session->setAdapter($files);
                        // $session->start();

                        // echo $session->get('name');
                        // echo $session->name;

                }
        

    }

    // public function checkAction() {
    //     if ($this->request->isPost()) {
    //         $email = $this->request->getPost('email');
    //         $password = $this->request->getPost('password');
    //         $this->view->users = Users::find(
    //             [
    //                 "email = '$email'",
    //                 "password = '$password'",
    //                 ]
    //             );
    //         $session = new Manager();
    //         $files = new Stream(
    //             [
    //                 'savePath' => '/tmp',
    //             ]
    //         );
    //         $session->setAdapter($files);
    //         $session->start();
    //     }
    // }  
    
}




// $this->view->user = Users::find
// ("conditions" => "email = $this->request->getPost('email')");

// $this->view->user = Users::query()
// ->where("email = :email:")
// ->bind(["email" => $this->request->getPost('email')])
// ->execute();

// $phql = "
// SELECT *
//     FROM users
//     WHERE email = $this->request->getPost('email')
//     AND password = $this->request->getPost('password')
// ";
// $this->view->user = $this->modelManager->executeQuery($phql);