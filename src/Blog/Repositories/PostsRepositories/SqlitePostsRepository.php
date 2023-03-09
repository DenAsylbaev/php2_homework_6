<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use \PDO;
// use Psr\Log\LoggerInterface;


class SqlitePostsRepository implements PostsRepositoryInterface
{
    private PDO $connection;
    // private LoggerInterface $logger;

    public function __construct(
        PDO $connection
        // LoggerInterface $logger
        ) 
        {
            $this->connection = $connection;
            // $this->logger = $logger;
        }
        
    public function save(Post $post)
    {        
        $statement = $this->connection->prepare(
            'INSERT INTO posts (post, author, title, txt)
            VALUES (:post, :author, :title, :txt)'
            );

            if(!$statement) {
                // выходим из функции и возвращаем false
                return false;
            } else {
                // выполняем запись в БД
                // Выполняем запрос с конкретными значениями
                $statement->execute([
                    ':post' => (string)$post->id(),
                    ':author' => (string)$post->getAuthorId(),
                    ':title' => $post->getTitle(),
                    ':txt' => $post->getText()
                ]);
                // выходим из функции и возвращаем true
                return true;
            }
    }
    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE post = ?'
        );

        $statement->execute([(string)$uuid]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $userRepo = new SqliteUsersRepository($this->connection); // чтоб юзера получить потом
        return new Post(
            new UUID($result['post']),
            $userRepo->get(new UUID($result['author'])),
            $result['title'],
            $result['txt']
        );
    }

    public function delete(UUID $uuid)
    {
        $statement = $this->connection->prepare(
            'DELETE FROM posts WHERE post = ?'
        );
        $statement->execute([(string)$uuid]);
    }
}