<?php
use Phalcon\Mvc\Controller;



class SelectController extends Controller
{
    public function indexAction()
    {
    }
    public function checkAction()
    {
        $post = $this->request->getPost();
        if ($post['email'] != '' && $post['password'] != '') {
            $conditions ="email = :email: AND password = :password:";
            $parameters = array(
                'email' => $post['email'],
                'password' => $post['password'],
            );
            $user = Users::findFirst(array(
                $conditions,
                'bind' => $parameters
            ));
            if(isset($user->id)) {
                $_SESSION['login'] = 'ok';
                $_SESSION['id'] = $user->id;
                $_SESSION['name'] = $user->name;
                header('Location: ../form');
            }else {
                $this->view->message = '一致する登録情報がありませんでした';
                $this->view->link = $this->tag->linkTo(['select', 'ログインページに戻る', 'class' => 'btn btn-primary']);
                
            }
        } else{
            $this->view->message = '未記入の項目があります';
            $this->view->link = $this->tag->linkTo(['select', 'ログインページに戻る', 'class' => 'btn btn-primary']);
            $this->view->register = $this->tag->linkTo(['register', '新規登録をする', 'class' => 'btn btn-primary']);
        }
    }
}