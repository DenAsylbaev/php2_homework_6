<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;

// use Psr\Log\LoggerInterface;

use \PDO;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    private PDO $connection;
    // private LoggerInterface $logger;

    public function __construct(
        PDO $connection,
        // LoggerInterface $logger
        ) 
        {
            $this->connection = $connection;
            // $this->logger = $logger;
        }
        
    public function save(Comment $comment)
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (comment, post, author, txt)
            VALUES (:comment, :post, :author, :txt)'
            );

            if(!$statement) {
                // выходим из функции и возвращаем false
                return false;
            } else {
                // Выполняем запрос с конкретными значениями
                $statement->execute([
                    ':comment' => $comment->id(),
                    ':post' => $comment->getPostId(),
                    ':author' => $comment->getAuthorId(),
                    ':txt' => $comment->getText()
                    ]);
                return true;
            }
    }
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE comment = ?'
        );
        $statement->execute([(string)$uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

// исключение, если не найден
        if (false === $result) {
            throw new CommentNotFoundException(
                "Cannot get comment: $uuid"
            );
        }
        $userRepo = new SqliteUsersRepository($this->connection); // чтоб юзера получить потом
        $postRepo = new SqlitePostsRepository(
            $this->connection,
            // $this->logger,
        ); // чтоб пост получить потом

        return new Comment(
            new UUID($result['comment']),
            $userRepo->get($result['author']),
            $postRepo->get($result['post']),
            $result['txt']        
        );
    }
}