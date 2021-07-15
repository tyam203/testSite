<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction()
    {

    }

    public function registerAction()
    {
        $user = new Users();

        $user->assign(
            $this->request->getPost(),
            [
                'name',
                'birthday',
                'gender',
                'email',
                'tel',
                'password'
            ]
        );

        $success = $user->save();

        $this->view->success = $success;

        if($success) {
            $message = '登録完了';
            $link = $this->tag->linkTo(['/', 'トップページに戻る', 'class' => 'btn btn-primary']);
        } else {
            $message = '登録に失敗しました:<br>'
            . implode('<br>', $user->getMessages());

            $link = $this->tag->linkTo(['/signup', '登録ページに戻る', 'class' => 'btn btn-primary']);

        }

        $this->view->message = $message;
        $this->view->link = $link;
    }
}