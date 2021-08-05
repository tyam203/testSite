<?php

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator\Alnum;
use Phalcon\Validation\Validator\Digit;
use Phalcon\Mvc\Dispatcher;

use Phalcon\Escaper;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Http\Response;


class CustomerController extends BaseController
{
    public $validation;

    public function validateCommon()
    {
        $this->validation = new Validation();
        $this->validation->add(
        [
            'email',
            'password',
        ],
        new PresenceOf([
                'message' => [
                    'email' => 'メールアドレスが入力されていません',
                    'password' => 'パスワードが入力されていません',
                ],
            ])
        );
        $this->validation->add(
            'email',
            new Email([
                    'message' => 'メールアドレスの形式が正しくありません',
                ])
        );
        $this->validation->add(
            'password',
            new Regex([
                    'message' => 'パスワードは英数字を含んだもの6文字以上32文字以下で入力してください',
                    'pattern' => '/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,32}+\z/i',
                ])
        );
    }

    public function validateLogin()
    {
        $this->validateCommon();
        $messages = $this->validation->validate($this->request->getPost());
        return $messages;
    }

    public function validateRegister()
    {
        $this->validateCommon();
        $this->validation->add(
            'name',
            new Regex([
                    'message' => '名前は漢字・ひらがな・カタカナのいずれかで入力してください',
                    'pattern' => '/^[ぁ-んァ-ヶー一-龠]+$/u',
                ])
        );
        $this->validation->add(
            'confirm',
            new Regex([
                    'message' => '確認用パスワードは英数字を含んだもの6文字以上32文字以下で入力してください',
                    'pattern' => '/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{6,32}+\z/i',
                ])
        );
        $this->validation->add(
            [
                'name',
                'birthday',
                'gender',
                'tel',
                'confirm',
            ],
            new PresenceOf([
                    'message' => [
                        'name' => '名前が入力されていません',
                        'birthday' => '生年月日が入力されていません',
                        'gender' => '性別が選択されていません',
                        'tel' => '電話番号が入力されていません',
                        'confirm' => '確認用パスワードが入力されていません',
                    ],
                ])
            );
        $this->validation->add(
            'tel',
            new Regex([
                    'message' => '正しい電話番号を入力してください',
                    'pattern' => '/^[0-9]{2,4}[0-9]{2,4}[0-9]{3,4}$/',
                ])
        );
        $this->validation->add(
            "birthday",
            new DateValidator([
                    "format"  => "Y-m-d",
                    "message" => "日付が正しくありません",
                ])
        );

        $this->validation->add(
            [
                'gender',
                'tel',
            ],
            new Digit([
                    'message' => [
                        'gender' => '性別の入力が正しくありません',
                        'tel' => '電話番号の入力が正しくありません',
                    ] ,
                ])
        );

        $this->validation->add(
            "password",
            new Confirmation([
                    "message" => "パスワードと確認用パスワードが一致しません",
                    "with"    => "confirm",
                ])
        );

        // $this->flashSession->error('test'); 
        $messages = $this->validation->validate($this->request->getPost());
        return $messages;
    }


// POSTで取得したemailとpasswordをもとにユーザー情報を取得
  public function checkRegistration($email, $password)
  {
      $conditions = "email = :email: AND password = :password:";
      $parameters = [
          'email' => $email,
          'password' => $password,
        ];
      $user = Users::findFirst(array(
          $conditions,
          'bind' => $parameters
      ));
      return $user;
  }

  public function loginAction()
  {
    $this->view->setVars([
        'email' => $this->session->email,
        'password' => $this->session->password,
    ]);
  }

  public function checkLoginAction()
  {
    $post = $this->request->getPost();
    $messages = $this->validateLogin();
    $this->session->set('email', $post['email']);
    $this->session->set('password', $post['password']);
    foreach($messages as $message) {
        $this->flashSession->error($message);
        if (count($messages)) {
            $this->response->redirect('customer/login');
        }
    }
    if (empty($post['email'] == false) && empty($post['password'] == false)) {
        $user = $this->checkRegistration($post['email'], $post['password']);
        if(isset($user->id)) {
            $this->session->set('id', $user->id);
            $this->session->set('name', $user->name);
            if (isset($this->session->reservation)) {
                $this->response->redirect('reservation/form');
                // $this->dispatcher->forward(
                //     [
                //         'controller' => 'reservation',
                //         'action' => 'form'
                //     ]
                //     );
                // header('Location: ../reservation/form');
            }
            $this->view->message = 'ログインしました';
            $this->view->link = $this->tag->linkTo(['../search', '検索ページへ', 'class' => 'btn btn-primary']);
        } else {
            $this->view->message = '一致する登録情報がありませんでした';
            if (isset($this->session->reservation)) {
                $this->view->link = $this->tag->linkTo(['customer/select', 'ログインページに戻る', 'class' => 'btn btn-primary']);
            } else {
                $this->view->link = $this->tag->linkTo(['customer/login', 'ログインページに戻る', 'class' => 'btn btn-primary']);
                $this->view->register = $this->tag->linkTo(['customer/signup', '新規登録をする', 'class' => 'btn btn-primary']);
            }
        }
    } else {
        $this->view->message = '未記入の項目があります';
        if (isset($this->session->reservation)) {
            $this->view->link = $this->tag->linkTo(['customer/select', 'ログインページに戻る', 'class' => 'btn btn-primary']);
        } else {
            $this->view->link = $this->tag->linkTo(['customer/login', 'ログインページに戻る', 'class' => 'btn btn-primary']);
            $this->view->register = $this->tag->linkTo(['customer/signup', '新規登録をする', 'class' => 'btn btn-primary']);
        }
    }
  }

  public function logoutAction()
  {
    $this->session->destroy();
    // session_unset();
  }

  public function selectAction()
  {
    $this->view->setVars([
        'email' => $this->session->email,
        'password' => $this->session->password,
    ]);
  }

  public function signupAction()
  {
      $this->view->setVars([
        'name' => $this->session->name,
        'birthday' => $this->session->birthday,
        'gender' => $this->session->gender,
        'email' => $this->session->email,
        'tel' => $this->session->tel,
        'password' => $this->session->password,
        'confirm' => $this->session->confirm,
      ]);
  }

  public function checkSignupAction()
  {
    $post = $this->request->getPost();
    $messages = $this->validateRegister();
    $this->session->set('name', $post['name']);
    $this->session->set('birthday', $post['birthday']);
    $this->session->set('gender', $post['gender']);
    $this->session->set('email', $post['email']);
    $this->session->set('tel', $post['tel']);
    $this->session->set('password', $post['password']);
    $this->session->set('confirm', $post['confirm']);

    foreach($messages as $message) {
        $this->flashSession->error($message);
        if (count($messages)) {
            $this->response->redirect('customer/signup');
        }
    }

    // $user = $this->checkRegistration($post['email'], $post['password']);
    $errors = array();
    $user = $this->checkRegistration($post['email'], $post['password']);
    if(isset($user->id)) { 
        $this->view->errors = 'ご記入の情報は既に登録されています<br>';
        $this->view->errors .= '別のメールアドレス・パスワードにて登録してください';
    } 

    $post = $this->request->getPost();
    if ($post['gender'] == 1) {
         $formedGender = '男性';
     } 
    if($post['gender'] == 2) {
         $formedGender = '女性';
     }
    
    // if (isset($errors)) {
    //     $this->view->errors = $errors;
    // }
    
    // foreach ($errors as $error) {
    //     $this->view->error = $error;
    // }
    $this->view->setVars([
          'name' => $post['name'],
          'birthday' => $post['birthday'],
          'gender' => $post['gender'],
          'formedGender' => $formedGender,
          'email' => $post['email'],
          'tel' => $post['tel'],
          'password' => $post['password'],
          'confirm' => $post['confirm'],
        ]);

  }

  public function completeSignUpAction()
  {
    $post = $this->request->getPost();
    $this->view->post = $post;
    $newUser = new Users();
    $newUser->assign([
        'name' => $post['name'],
        'birthday' => $post['birthday'],
        'gender' => $post['gender'],
        'email' => $post['email'],
        'tel' => $post['tel'],
        'password' => $post['password'],
      ]);
        
    $success = $newUser->save();    
    $this->view->success = $success;
        
    if($success == true) {
      $user = $this->checkRegistration($post['email'], $post['password']);

      $this->session->set('id', $user->id);
      $this->session->set('name', $user->name);
      $this->view->message = '新規登録が完了しました';
      $this->view->link = $this->tag->linkTo(['reservation/form', '予約手続きを進める', 'class' => 'btn btn-primary']);
    } else {
        $this->view->message = '登録に失敗しました<br>';
        $this->view->link = $this->tag->linkTo(['customer/signup', '登録ページに戻る', 'class' => 'btn btn-primary']);
    }         
  }
}