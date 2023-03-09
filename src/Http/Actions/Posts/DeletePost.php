<?php
namespace GeekBrains\LevelTwo\Http\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\UUID;

use Psr\Log\LoggerInterface;

class DeletePost implements ActionInterface
{
    private PostsRepositoryInterface $postsRepository;
    private LoggerInterface $logger;

    // Внедряем репозитории статей и пользователей
    public function __construct(
        PostsRepositoryInterface $postsRepository,
        LoggerInterface $logger
    ) {
        $this->postsRepository = $postsRepository;
        $this->logger = $logger;
    }

    public function handle(Request $request): Response
    {
        // Пытаемся создать UUID поста из данных запроса
        try {
            $postId = $request->query('uuid');
            $postUuid = new UUID($postId);

        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        // Пытаемся найти пост в репозитории и удалить
        try {
            $this->postsRepository->delete($postUuid);
            // Логируем действие удаления статьи
            $this->logger->info("Post deleted: $postUuid");

        } catch (UserNotFoundException $e) {
            // Логируем сообщение с уровнем WARNING
            $this->logger->warning("Can't delete this post: $postUuid");

            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            'deleted post uuid' => (string)$postUuid,
        ]);
    }
}