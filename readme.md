# PoopExpress

It's a router. It's fast. Don't use it.

Using it is a shitty experience, hence PoopExpress.

## Usage

You start by initializing the router, with your request details

```php
$router = new PoopExpress($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
```

You then attempt routes, rather than registering them
```php
$router->attempt([''], ['GET', function () {
    echo 'Home page';
}]) || $router->attempt(['somewhere-else'], ['GET' => function () {
    echo 'This is a different page';
}]);
```
This gives you 2 endpoints, with automatic 405 handling:
- `/`
- `/somewhere-else`

Dynamic routes are an option
```php
$router->attempt(['group', '*'], ['GET' => function ($id) {
    echo 'This page is within the "group", ', $id;
}]);
```

And for optimal performance, use this garbage syntax to seperate your routes into groups:
```php
(
    $router->group(['group']) && (
        $router->attempt(['group', 'nowhere'], ['GET' => function () {
            echo 'This page is also within the "group"';
        }]) || $router->attempt(['group', 'some-other-group'], ['POST' => function () {
            echo 'This page is yet again within the "group"';
        }])
    )
)
```

All together:
```php
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
```

### How is this fast?

Routes are never registered, and when the "current" route is attempted, parsing of any other route never occurs to any degree. With groups and funky and-gates you can skip the checking of sub-routes if the parent does not match.

### Why shouldn't I use it?

If your app is slow, it's very unlikely throwing a new router into the mix will solve your problems.

It's also just plain painful to try to use this.
