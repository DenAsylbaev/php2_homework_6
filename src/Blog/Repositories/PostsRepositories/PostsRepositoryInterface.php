<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepositories;

// use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;


interface PostsRepositoryInterface
{
    public function save(Post $post);
    public function get(UUID $uuid): Post;    
    public function delete(UUID $uuid);    

}