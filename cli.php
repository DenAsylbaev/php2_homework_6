<?php

use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;

use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;

use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories\CommentsRepositoryInterface;

use GeekBrains\LevelTwo\Blog\Repositories\LikesRepositories\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepositories\SqliteLikesRepository;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Commands\Arguments;



use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Person\Name;
use GeekBrains\LevelTwo\Blog\Exceptions\AppException;

use Psr\Log\LoggerInterface;



// Подключаем файл bootstrap.php
// и получаем настроенный контейнер

$container = require __DIR__ . '/bootstrap_new.php';

// var_dump($container);
// die();

$logger = $container->get(LoggerInterface::class);
// var_dump($logger);
// die();


// При помощи контейнера создаём команду
// $command = $container->get(PostsRepositoryInterface::class);
// $command_user = $container->get(UsersRepositoryInterface::class);
$command_likes = $container->get(LikesRepositoryInterface::class);

try {

    // При помощи контейнера создаём команду
    $command = $container->get(CreateUserCommand::class);
    $command->handle(Arguments::fromArgv($argv));

} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    echo $e->getMessage();
}

// var_dump($logger);
// die();



// var_dump($command_user);
// die();


// $userFromDB_2 = $command_user->get(new UUID('743bdbc3-3478-4d4f-91e1-cc93ca38e216'));

// $likeId = UUID::random();

// $newPost = $command->get(new UUID('c1e21670-7f82-4bc4-b3f8-c0f5d57af8f8'));

// $like = new Like(
//     $likeId,
//     $userFromDB_2,
//     $newPost
// );

// try {
//     $command_likes->save($like);
// } catch (AppException $e) {
//     echo "{$e->getMessage()}\n";
// }

// $likeArray = $command_likes->getByPostUuid(new UUID('c1e21670-7f82-4bc4-b3f8-c0f5d57af8f8'));

// print_r($likeArray);



// $post1 = new Post(
//     $postId1,
//     $userFromDB_2,
//     'POST_FROM_DEN_HELLO',
//     'HELLO-BYES'
// );
// print_r('КОММАНД - ') .PHP_EOL;
// var_dump($command) .PHP_EOL;
// die();


// try {
//     $command->handle(Arguments::fromArgv($argv));
// } catch (AppException $e) {
//     echo "{$e->getMessage()}\n";
// }

// try {
//     $command->save($post1);
// } catch (AppException $e) {
//     echo "{$e->getMessage()}\n";
// }


//СТАРЫЙ КОД
// require_once __DIR__ . '/vendor/autoload.php';


// $connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

// $postRepository = new SqlitePostsRepository($connection);
// $userRepository = new SqliteUsersRepository($connection);
// $commentRepository = new SqliteCommentsRepository($connection);


// $name1 = new Name('Denis', 'Denisov');
// $name2 = new Name('Maxim', 'Maximov');
// $name3 = new Name('Murad', 'Legenda');


// $user3 = new User(
//     UUID::random(),
//     'Murad',
//     $name3
// );

// $user2 = new User(
//     UUID::random(),
//     'Max',
//     $name2
// ); 

// $userFromDB = $userRepository->get(new UUID(''));
// $postFromDB = $postRepository->get(new UUID(''));

// $postId2 = UUID::random();
// $postId3 = UUID::random();

// $commentId = UUID::random();

// $comment = new Comment(
//     $commentId,
//     $userFromDB,
//     $postFromDB,
//     'comment_1'
// );


// $post1 = new Post(
//     $postId1,
//     $userFromDB_2,
//     'POST_FROM_DEN_HELLO',
//     'HELLO-BYES'
// );
// $post2 = new Post(
//     $postId2,
//     $user2,
//     'POST_FROM_MAX',
//     'HELLOOO'
// );

// $commentRepository->save($comment);
// $postRepository->save($post1);
// $postRepository->save($post2);


// $userRepository->save($user1);
// $userRepository->save($user2);
// $userRepository->save($user3);



// $newPost = $postsRepository->get(new UUID(''));
// $newUser = $userRepository->get(new UUID(''));


// $postRepository->delete(new UUID('912b016c-15d3-4459-ae7f-88c8eb9ffc84'));