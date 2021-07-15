<?php
use Phalcon\Mvc\Controller;



class LogoutController extends Controller
{
    

    public function indexAction()
    {
        // ログアウトする場合、セッション削除してトップページへ（ログインページへ）
        if (!empty($_POST['yes']) === true){
            session_destroy();
            header('Location: ../');
            exit();
        } if (!empty($_POST['no']) === true){
            header('Location: ../index');
        }
    }
}