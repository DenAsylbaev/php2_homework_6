<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog;

class Comment
{
    // private int $id;
    private UUID $id;
    private UUID $authorId;
    private string $postId;
    private string $text;

    public function __construct(
        // int $id,
        UUID $id,
        User $author,
        Post $post,
        string $text
    ) {
        $this->id = $id;
        $this->authorId = $author->id();
        $this->postId = $post->id();
        $this->text = $text;
}
    public function __toString()
    {
    return  'Пользователь ' . $this->authorId . ' оставил комментарий к посту ' . $this->postId .': ' . $this->text . PHP_EOL;

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
    public function getText(): string
    {
        return $this->text;
    }
}