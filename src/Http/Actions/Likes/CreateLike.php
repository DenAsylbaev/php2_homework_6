<?php
namespace GeekBrains\LevelTwo\Http\Actions\Likes;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepositories\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use Psr\Log\LoggerInterface;


class CreateLike implements ActionInterface
{
    private LikesRepositoryInterface $likesRepository;
    private PostsRepositoryInterface $postsRepository;
    private UsersRepositoryInterface $usersRepository;
    private LoggerInterface $logger;



    // Внедряем репозитории статей и пользователей
    public function __construct(
        LikesRepositoryInterface $likesRepository,
        PostsRepositoryInterface $postsRepository,
        UsersRepositoryInterface $usersRepository,
        LoggerInterface $logger
    ) {
        $this->likesRepository = $likesRepository;
        $this->postsRepository = $postsRepository;
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

    public function handle(Request $request): Response
    {
        // Пытаемся создать UUID пользователя из данных запроса
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));

        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти пользователя в репозитории
        try {
            $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }


        // Пытаемся создать UUID статьи из данных запроса
        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти эту статью в репозитории
        try {
            $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        

        //проверка заданного поста на наличие лайка от заданного юзера

        if (!$this->chekLike($request, $postUuid)) {
            // Генерируем UUID для лайка
            try {
                $newLikeUuid = UUID::random();
                // Пытаемся создать объект лайка
                // из данных запроса
                $like = new Like(
                $newLikeUuid,
                $this->usersRepository->get($authorUuid),
                $this->postsRepository->get($postUuid)
                );
            } catch (HttpException $e) {
                return new ErrorResponse($e->getMessage());
            }

            // Сохраняем лайк в репозиторий лайков
            $this->likesRepository->save($like);

            // Возвращаем успешный ответ,
            // содержащий UUID
            return new SuccessfulResponse([
                'new like' => (string)$newLikeUuid
            ]);
        } else {
            try {
                $this->likesRepository->delete(
                    $authorUuid,
                    $postUuid
                );
            } catch (HttpException $e) {
                return new ErrorResponse($e->getMessage());
            }

            // Возвращаем успешный ответ,
            // содержащий информацию об удаленном лайке
            return new SuccessfulResponse([
                'revoked like from user' => (string)$authorUuid
            ]);
        }
        
    }
    
    //проверка оставлял ли данный юзер лайк под данным постом
    public function chekLike($request, $postUuid) {
    //помещаем в переменную массив лайков под данным постом
        $likeArray = $this->likesRepository->getByPostUuid($postUuid);

    //ищем в массиве юзера
        foreach($likeArray as $key => $value) {
            if (in_array($request->jsonBodyField('author_uuid'), $value)) {
                return true; // если лайк от этого пользователя уже есть
            }
        }
        return false; // если нет лайка от этого пользователя
    }
}