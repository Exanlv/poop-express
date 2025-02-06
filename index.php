<?php

use Exan\PoopExpress\PoopExpress;

require_once './src/PoopExpress.php';

$router = new PoopExpress($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$router->attempt([''], ['GET', function () {
    echo 'Home page';
}]) || $router->attempt(['somewhere-else'], ['GET' => function () {
    echo 'This is a different page';
}]) || (
    $router->group(['group']) && (
        $router->attempt(['group', 'nowhere'], ['GET' => function () {
            echo 'This page is also within the "group"';
        }]) || $router->attempt(['group', 'some-other-group'], ['POST' => function () {
            echo 'This page is yet again within the "group"';
        }]) || $router->attempt(['group', '*'], ['GET' => function ($id) {
            echo 'This page is within the "group", ', $id;
        }])
    )
) || $router->attempt(['not-group', 'page'], ['GET' => function () {
    echo 'This is no longer in the group';
}]) || (function () {
    http_response_code(404);
    echo '404';
})();
