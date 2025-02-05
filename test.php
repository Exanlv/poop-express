<?php

use Exan\PoopExpress\PoopExpress;

require_once 'vendor/autoload.php';

$start = microtime(true);

ob_start();

for ($i = 0; $i < 100000; $i++) {
    $router = new PoopExpress('GET', '/not-group/page');

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
            })
        )
    ) || $router->attempt('GET', '/^\/not-group\/page$/', function () {
        echo 'This is no longer in the group';
    }) || $router->default();

    ob_clean();
}

ob_flush();

echo microtime(true) - $start, PHP_EOL;
