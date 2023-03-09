<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;

use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;


use \PDO;
use \PDOStatement;

class DummyPostsRepository implements PostsRepositoryInterface
{
    private $connection;
    public function __construct($connection) 
        {
            $this->connection = $connection;
        }
        
    public function save(Post $post): void
        {
            $this->connection[] = $post;
        }
    public function get($uuid): Post
        {
            throw new PostNotFoundException("Post is not found");
        }
}