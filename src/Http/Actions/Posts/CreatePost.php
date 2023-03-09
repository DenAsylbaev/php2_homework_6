<?php
namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;

use GeekBrains\LevelTwo\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Http\Auth\JsonBodyUuidIdentification;

use Psr\Log\LoggerInterface;

class CreatePost implements ActionInterface
{
    private PostsRepositoryInterface $postsRepository;
    // private UsersRepositoryInterface $usersRepository;
    private IdentificationInterface $identification;
    // Внедряем контракт логгера
    private LoggerInterface $logger;

    // Внедряем репозитории статей и пользователей
    public function __construct(
        PostsRepositoryInterface $postsRepository,
        // UsersRepositoryInterface $usersRepository,
        IdentificationInterface $identification,
        LoggerInterface $logger
    ) {
        $this->postsRepository = $postsRepository;
        // $this->usersRepository = $usersRepository;
        $this->identification = $identification;
        $this->logger = $logger;
    }

    public function handle(Request $request): Response
    {
        // Идентифицируем пользователя -
        // автора статьи
        $author = $this->identification->user($request);

        // // Генерируем UUID для новой статьи
        $newPostUuid = UUID::random();

        try {
            // Пытаемся создать объект статьи
            // из данных запроса
            $post = new Post(
            $newPostUuid,
            $author,
            $request->jsonBodyField('title'),
            $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        
        // Сохраняем новую статью в репозитории
        if($this->postsRepository->save($post)) {
            // Логируем UUID новой статьи
            $this->logger->info("Post created: $newPostUuid"); 
            // Возвращаем успешный ответ,
            // содержащий UUID новой статьи
            return new SuccessfulResponse([
                'uuid' => (string)$newPostUuid,
            ]);
        } else {
            // Логируем сообщение с уровнем WARNING
            // $this->logger->warning("Can't create post");
            // и возвращаем неуспешынй ответ
            return new ErrorResponse("Can't create post");
        }
    }
}