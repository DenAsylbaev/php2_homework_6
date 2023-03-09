<?php
namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;

use Psr\Log\LoggerInterface;


// Класс реализует контракт действия
class FindPostByUuid implements ActionInterface
{
    private PostsRepositoryInterface $postsRepository;
    private LoggerInterface $logger;

    // Нам понадобится репозиторий постов,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        PostsRepositoryInterface $postsRepository,
        LoggerInterface $logger
        ) 
    {
        $this->postsRepository = $postsRepository;
        $this->logger = $logger;
    }

// Функция, описанная в контракте
    public function handle(Request $request): Response
    {
        try {
            // Пытаемся получить искомый пост из запроса
            $postId = $request->query('post');

        } catch (HttpException $e) {
            // Если в запросе нет параметра uuid -
            // возвращаем неуспешный ответ,
            // сообщение об ошибке берём из описания исключения
            return new ErrorResponse($e->getMessage());
        }
        try {
            // Пытаемся найти пост в репозитории
            $post = $this->postsRepository->get(new UUID($postId));

        } catch (PostNotFoundException $e) {
            
            // Если пост не найден:
            // делаем записть в лог
            $this->logger->warning("Can't find this post: $postId");
            // и возвращаем неуспешный ответ
            return new ErrorResponse($e->getMessage());
        }
            // Возвращаем успешный ответ
            return new SuccessfulResponse([
            'id' => $post->id(),
            'title' => $post->getTitle(),
            'text' => $post->getText()
        ]);
    }
}