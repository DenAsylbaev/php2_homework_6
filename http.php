<?php

use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepositories\SqliteLikesRepository;

use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\Http\Actions\Posts\FindPostByUuid;
use GeekBrains\LevelTwo\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\Http\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\Http\Actions\Comments\CreateComment;
use GeekBrains\LevelTwo\Http\Actions\Likes\CreateLike;

use GeekBrains\LevelTwo\Http\Auth\JsonBodyUuidIdentification;

use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap_new.php';


// Создаём объект запроса из суперглобальных переменных
$request = new Request(
    $_GET, 
    $_SERVER,
    file_get_contents('php://input'),
);

$logger = $container->get(LoggerInterface::class);


try {
    // Пытаемся получить путь из запроса
    $path = $request->path();

} catch (HttpException $e) {
    // Логируем сообщение с уровнем WARNING
    $logger->warning($e->getMessage());

    // Отправляем неудачный ответ,
    // если по какой-то причине
    // не можем получить путь
    (new ErrorResponse($e->getMessage()))->send();

    // Выходим из программы
    return;
}

$routes = [
    // Добавили ещё один уровень вложенности
    // для отделения маршрутов,
    // применяемых к запросам с разными методами
    'GET' => [
        '/users/show' => FindByUsername::class,
        '/posts/show' =>  FindPostByUuid::class,
    ],
    'POST' => [
        // Добавили новый маршрут
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/like' => CreateLike::class
    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
    ],
];

$method = $request->method();

// print_r($container);
// die();

// Если у нас нет маршрута для пути из запроса -
// отправляем неуспешный ответ
if (!array_key_exists($method, $routes)
    || !array_key_exists($path, $routes[$method])) {
    
    // Логируем сообщение с уровнем NOTICE
    $message = "Route not found: $method $path";
    $logger->notice($message);

    (new ErrorResponse('Not found'))->send();
    return;
}
// Ищем маршрут среди маршрутов для этого метода
if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();

    return;
}

// Выбираем действие по методу и пути

$actionClassName = $routes[$method][$path];
// print_r($actionClassName);
// die();

$action = $container->get($actionClassName);
// print_r('CONTAINER' .PHP_EOL);
// print_r($container);
// print_r('ACTION' .PHP_EOL);
// print_r($actionClassName .PHP_EOL);
// print_r('FINISH' .PHP_EOL);
// print_r($action);
// die();


try {
    $response = $action->handle($request);
} catch (AppException $e) {
    // Логируем сообщение с уровнем ERROR
    $logger->error($e->getMessage(), ['exception' => $e]);

    (new ErrorResponse($e->getMessage()))->send();
}
$response->send();
