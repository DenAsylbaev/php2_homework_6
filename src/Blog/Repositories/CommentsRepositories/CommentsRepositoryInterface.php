<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepositories;

// use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;


interface CommentsRepositoryInterface
{
    public function save(Comment $comment);
    public function get(UUID $uuid): Comment;    
}