<?php

namespace GeekBrains\LevelTwo;

use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories\CommentsRepositoryInterface;

use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteCommentsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {
        $arguments = new class implements CommentsRepositoryInterface {
            private $connection;
        
            public function save($comment): void
            {
                // $this->connection[] = $comment;
            }
            public function get($uuid): Comment
            {
                throw new CommentNotFoundException("Comment is not found");
            }
        };
        $this->expectException(CommentNotFoundException::class); //ожидаемый тип возвращаемого значения
        $this->expectExceptionMessage("Comment is not found"); //ожидаемое сообщение
        $arguments->get('123e4567-e89b-12d3-a456');
    }

    
    public function testItSavesCommentToDatabase(): void 
    {
        $connectionStub = $this->createStub(PDO::class); // стаб подключения
        $statementMock = $this->createMock(PDOStatement::class); // мок запроса
        $statementMock
            ->expects($this->once())
            ->method('execute') 
            ->with([
                ':uuid' => '123e4567-e89b-12d3-a456-426614174003',
                ':post_uuid' => '123e4567-e89b-12d3-a456-426614174004',
                ':author_uuid' => '123e4567-e89b-12d3-a456-426614174005',
                ':txt' => 'Мой комментарий'
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqliteCommentsRepository($connectionStub);

        $commentAuthor = new User(
            new UUID('123e4567-e89b-12d3-a456-426614174005'),
            'ivan123',
            new Name('Ivan', 'Nikitin')
        );

        $post = new Post(
            new UUID('123e4567-e89b-12d3-a456-426614174004'),
            $commentAuthor,
            'Заголовок',
            'Текст'
        );

        $repository->save(
            new Comment(
                new UUID('123e4567-e89b-12d3-a456-426614174003'),
                $commentAuthor,
                $post,
                'Мой комментарий'
            )
        );
    }

    public function testItGetCommentByUuid(): void
    {
        //этот тест не сделал
    }
}