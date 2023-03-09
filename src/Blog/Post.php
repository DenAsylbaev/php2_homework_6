<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog;

class Post
{
    // private int $id;
    private UUID $id;
    private UUID $authorId;
    private string $title;
    private string $text;

    public function __construct(
        // int $id,
        UUID $id,
        User $author,
        string $title,
        string $text
    ) {
        $this->id = $id;
        $this->authorId = $author->id();
        $this->title = $title;
        $this->text = $text;
}
    public function __toString()
    {
    return  'Пользователь ' . $this->authorId . ' пишет: ' . $this->title . '/ ' . $this->text . PHP_EOL;

    }
    public function id(): string
    {
        return $this->id;
    }

    public function getAuthorId(): UUID
    {
        return $this->authorId;
    }
    public function getText(): string
    {
        return $this->text;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
}