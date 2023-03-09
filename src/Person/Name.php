<?php

namespace GeekBrains\LevelTwo\Person;

class Name
{
    private string $firstName;
    private string $lastName;
    
    public function __construct(
        string $firstName,
        string $lastName
    ) {
        $this->lastName = $lastName;
        $this->firstName = $firstName;
    }
    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
}