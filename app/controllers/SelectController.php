<?php
use Phalcon\Mvc\Controller;



class SelectController extends Controller
{
    public function indexAction()
    {
    }
    public function checkAction()
    {
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
            $_SESSION['login'] = 'ok';
            foreach($users as $user) {
                $_SESSION['id'] = $user->id;
                $_SESSION['name'] = $user->name;
            }
        }     
        header('Location: ../form');
        
    }
}
