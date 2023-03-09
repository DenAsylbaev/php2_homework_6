<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepositories;

use GeekBrains\LevelTwo\Blog\Exceptions\LikesNotFoundException;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\UUID;
use \PDO;

class SqliteLikesRepository implements LikesRepositoryInterface
{
    private PDO $connection;
    public function __construct(PDO $connection) 
        {
            $this->connection = $connection;
        }
        
    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (like, post, author)
            VALUES (:like, :post, :author)'
            );
            // Выполняем запрос с конкретными значениями
            $statement->execute([
            ':like' => $like->id(),
            ':post' => $like->getPostId(),
            ':author' => $like->getAuthorId()
            ]);
            
    }
    public function getByPostUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE post = ?'
        );
        $statement->execute([(string)$uuid]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

// исключение, если не найден
        if (false === $result) {
            throw new LikesNotFoundException(
                "Cannot get likes for this post: $uuid"
            );
        }
        return $result;
    }
    public function delete($authorUuid, $postUuid)
    {
        $statement = $this->connection->prepare(
            'DELETE FROM likes WHERE post = :post AND author = :author'
        );

        $statement->execute([
            'post' => $postUuid,
            'author' => $authorUuid
        ]);
    }
}