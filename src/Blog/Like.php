<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog;

class Like
{
    // private int $id;
    private UUID $id;
    private string $postId;
    private string $authorId;

    public function __construct(
        UUID $id,
        User $author,
        Post $post
    ) {
        $this->id = $id;
        $this->authorId = $author->id();
        $this->postId = $post->id();
}
    public function __toString()
    {
    return  'Пользователь ' . $this->authorId . ' оценил запись ' . $this->postId . PHP_EOL;

    }
    public function id(): string
    {
        return $this->id;
    }
    public function getAuthorId(): string
    {
        return $this->authorId;
    }
    public function getPostId(): string
    {
        return $this->postId;
    }
}