<?php
$di->setShared('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
});