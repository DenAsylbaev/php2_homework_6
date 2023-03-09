<?php

namespace GeekBrains\LevelTwo;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories\DummyPostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $arguments = new DummyPostsRepository(''); //передаем пустую строку в стаб
        $this->expectException(PostNotFoundException::class); //ожидаемый тип возвращаемого значения
        $this->expectExceptionMessage("Post is not found"); //ожидаемое сообщение
        $arguments->get('123e4567-e89b-12d3-a456');
    }

    public function testItSavesPostToDatabase(): void 
    {
        $connectionStub = $this->createStub(PDO::class); // стаб подключения
        $statementMock = $this->createMock(PDOStatement::class); // мок запроса
        $statementMock
            ->expects($this->once()) // Ожидаем, что будет вызван один раз
            ->method('execute') // метод execute
            ->with([ // с единственным аргументом - массивом
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':author_uuid' => '123e4567-e89b-12d3-a456-426614174001',
                ':title' => 'Заголовок',
                ':txt' => 'Текст сообщения',
            ]);
        
            $connectionStub->method('prepare')->willReturn($statementMock);
            $repository = new SqlitePostsRepository($connectionStub);

            $repository->save(
                new Post(
                    new UUID('123e4567-e89b-12d3-a456-426614174000'),
                    new User(
                        new UUID('123e4567-e89b-12d3-a456-426614174001'),
                        'ivan123',
                        new Name('Ivan', 'Nikitin')
                    ),
                    'Заголовок',
                    'Текст сообщения'
                )
            );
    }

    public function testItGetPostByUuid(): void 
    {
        // этот тест не доделал

        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '9dba7ab0-93be-4ff4-9699-165320c97694'
        ]);
        $connectionStub->method('prepare')->willReturn($statementMock);

        $postRepository = new SqlitePostsRepository($connectionStub);
        $post = $postRepository->get(new UUID('9dba7ab0-93be-4ff4-9699-165320c97694'));

        $this->assertSame('9dba7ab0-93be-4ff4-9699-165320c97694', (string)$post->id());
    }
}