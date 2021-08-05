<?php

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Url;
use Phalcon\Mvc\Application;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Escaper;
use Phalcon\Flash\Session as FlashSession;

date_default_timezone_set('Asia/Tokyo');

// BASE_PATH：そのファイルが存在するディレクトリ
// dirname: 親ディレクトリのパスを返す
// c\home\hotelSite\
// APP_PATH：
// c\home\hotelSite/app
// 次どこで使っているか確認
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');


// コントローラーとモデルをオートローダーに追加
$loader = new Loader();
$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);

$loader->register();



// Create a DI
$container = new FactoryDefault();


// Setup the view component
$container->set(
    'view',
    function () {
        $view = new View(); //新しいviewを作る
        $view->setViewsDir(APP_PATH . '/views/'); //パスを「/views/」に指定する
        return $view; //変数viewを返します
    }
);


// Setup a base URI
$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);



$application = new Application($container);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => '127.0.0.1',
                'username' => 'root',
                'password' => 'mairo0510',
                'dbname'   => 'hotelSite',
            ]
        );
    }
);

$container->set('session', function() {
    $session = new Manager();
    $files = new Stream(
        [
            'savePath' => 'C:\php72\tmp',
        ]
    );
    $session->setAdapter($files);
    $escaper = new Escaper();
    $flash   = new FlashSession($escaper, $session);
    $session->start();
    return $session;
});

try {

    // 'REQUEST_URI'
    // ページにアクセスするために指定された URI。例えば、 '/index.html'
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );
    
    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}



