<?php
namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Name;


class User
{
    private UUID $id;
    private Name $name;
    private string $username;

    public function __construct(UUID $id, string $username, Name $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
    }

    public function __toString(): string
    {
        return "Юзер $this->id с именем $this->name и никнеймом $this->username." . PHP_EOL;

    }

    public function id(): UUID
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function setUserName(string $username): void
    {
        $this->username = $username;
    }

}