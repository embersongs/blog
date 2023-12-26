<?php

use Ember\Blog\Exceptions\AppException;
use Ember\Commands\AddLikeComment;
use Ember\Commands\AddLikePost;
use Ember\Http\Auth\LogIn;
use Ember\Http\Actions\Users\FindByUsername;
use Ember\Http\Auth\LogOut;
use Ember\Http\ErrorResponse;
use Ember\Http\HttpException;
use Ember\Http\Request;
use Ember\Http\Actions\Posts\CreatePost;
use Ember\Http\Actions\Posts\CreateComment;
use Ember\Http\Actions\Posts\DeletePost;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

$logger = $container->get(LoggerInterface::class);

try {
    $path = $request->path();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
        /*'/posts/show' => FindByUuid::class,*/

    ],
    'POST' => [
        '/login' => LogIn::class,
        '/logout' => LogOut::class,
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/likes/add' => AddLikePost::class,
        '/comments/likes/add' => AddLikeComment::class,
    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
    ],
];

if (!array_key_exists($method, $routes)
    || !array_key_exists($path, $routes[$method])) {

    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}


// Получаем имя класса действия для маршрута
$actionClassName = $routes[$method][$path];


// С помощью контейнера
// создаём объект нужного действия
$action = $container->get($actionClassName);

try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
// Логируем сообщение с уровнем ERROR
    $logger->error($e->getMessage(), ['exception' => $e]);
// Больше не отправляем пользователю
// конкретное сообщение об ошибке,
// а только логируем его
    (new ErrorResponse)->send();
    return;
}
$response->send();

