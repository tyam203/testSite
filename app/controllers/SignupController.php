<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction()
    {

    }

    public function checkAction()
    {
        $post = $this->request->getPost();
        $errors = array();

        $conditions ="email = :email: AND password = :password:";
            $parameters = array(
                'email' => $post['email'],
                'password' => $post['password'],
            );
            $user = Users::findFirst(array(
                $conditions,
                'bind' => $parameters
            ));
        $this->view->userId = $user->id;
        if(isset($user->id)) { 
            $errors['data'] = 'ご記入の情報は既に登録されています<br>';
            $errors['data'] .= '別のメールアドレス・パスワードにて登録してください';
        } 
        if ($post['name'] == '') {
            $errors['name'] = '名前が入力されていません';
        } 
        if ($post['birthday'] == '') {
            $errors['birthday'] = '生年月日が入力されていません';
        } 
        if ($post['gender'] == 0) {
            $errors['gender'] = '性別が選択されていません';
        } 
        if ($post['email'] == '') {
            $errors['email'] = 'メールアドレスが入力されていません';
        } 
        if ($post['tel'] == '') {
            $errors['tel'] = '電話番号が入力されていません';
        } 
        if ($post['password'] == '') {
            $errors['password'] = 'パスワードが入力されていません';
        } 
        
        if ($post['gender'] == 1) {
            $formedGender = '男性';
        } 
        if($post['gender'] == 2) {
            $formedGender = '女性';
        }
        
        if (isset($errors)) {
            $this->view->errors = $errors;
        }
        
        foreach($errors as $error){
            $this->view->error = $error;
        }
        $this->view->setVars(
            [
              'name' => $post['name'],
              'birthday' => $post['birthday'],
              'gender' => $post['gender'],
              'formedGender' => $formedGender,
              'email' => $post['email'],
              'tel' => $post['tel'],
              'password' => $post['password'],
            ]
            );
        }
        
        public function registerAction()
        {
            $post = $this->request->getPost();
            $this->view->post = $post;
            $newUser = new Users();
            $newUser->assign(
              [
                'name' => $post['name'],
                'birthday' => $post['birthday'],
                'gender' => $post['gender'],
                'email' => $post['email'],
                'tel' => $post['tel'],
                'password' => $post['password'],
              ]
            );
                
            $success = $newUser->save();    
            $this->view->success = $success;
                
            if($success == true) {
              $conditions ="email = :email: AND password = :password:";
              $parameters = array(
                  'email' => $post['email'],
                  'password' => $post['password'],
              );
              $user = Users::findFirst(array(
                  $conditions,
                  'bind' => $parameters
              ));
              $_SESSION['login'] = 'ok';
              $_SESSION['id'] = $user->id;
              $_SESSION['name'] = $user->name;
              $this->view->message = '新規登録が完了しました';
              $this->view->link = $this->tag->linkTo(['form', '予約手続きを進める', 'class' => 'btn btn-primary']);
            } else {
                $this->view->message = '登録に失敗しました:<br>';
                $this->view->link = $this->tag->linkTo(['/signup', '登録ページに戻る', 'class' => 'btn btn-primary']);
            }         
    }
}