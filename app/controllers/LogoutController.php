<?php
use Phalcon\Mvc\Controller;



class LogoutController extends Controller
{
    

    public function indexAction()
    {
        $post = $this->request->getPost();
        // ログアウトする場合、セッション削除してトップページへ（ログインページへ）
        if (!empty($post['yes']) === true){
            session_unset();
            header('Location: ../');
            exit();
        } if (!empty($post['no']) === true){
            header('Location: ../index');
        }
    }
}