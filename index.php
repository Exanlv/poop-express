<?php

use Exan\PoopExpress\PoopExpress;

require_once 'vendor/autoload.php';

$router = new PoopExpress($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$router->attempt('GET', '/^\/$/', function () {
    echo 'Home page';
}) || $router->attempt('GET', '/^\/somewhere-else$/', function () {
    echo 'This is a different page';
}) || (
    $router->group('/^\/group\//') &&
    (
        $router->attempt('GET', '/^\/group\/(\d*?)$/', function ($id) {
            echo 'This page is within the "group", ', $id;
        }) || $router->attempt('GET', '/^\/group\/some-other-group$/', function () {
            echo 'This page is also within the "group"';
        }) || $router->attempt('POST', '/^\/group\/nowhere$/', function () {
            echo 'This page is also within the "group"';
        })
    )
) || $router->attempt('GET', '/^\/not-group\/page$/', function () {
    echo 'This is no longer in the group';
}) || $router->default();
