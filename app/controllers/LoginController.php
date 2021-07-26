<?php
use Phalcon\Mvc\Controller;



class LoginController extends Controller
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
                $this->view->message = 'ログインしました';
                $this->view->link = $this->tag->linkTo(['/', '検索ページへ', 'class' => 'btn btn-primary']);
            }else {
                $this->view->message = '一致する登録情報がありませんでした';
                $this->view->link = $this->tag->linkTo(['login', 'ログインページに戻る', 'class' => 'btn btn-primary']);
                
            }
            // }
        }else{
            $this->view->message = '未記入の項目があります';
            $this->view->link = $this->tag->linkTo(['login', 'ログインページに戻る', 'class' => 'btn btn-primary']);
        }
    }
    
}