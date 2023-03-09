<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;


//php cli.php username=ivan first_name=Ivan last_name=Nikitin

class CreateUserCommand

{
    private UsersRepositoryInterface $usersRepository;
    private LoggerInterface $logger;

// Команда зависит от контракта репозитория пользователей,
// а не от конкретной реализации
    public function __construct(
        UsersRepositoryInterface $usersRepository, 
        LoggerInterface $logger
        )
    {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");
        
        $username = $arguments->get('username');

// Проверяем, существует ли пользователь в репозитории
        if ($this->userExists($username)) {
            
            // Логируем сообщение с уровнем WARNING
            $this->logger->warning("User already exists: $username");
            // Вместо выбрасывания исключения просто выходим из функции
            return;
        }
        // Сохраняем пользователя в репозиторий
        $this->usersRepository->save(new User(
            UUID::random(),
            $username,
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name'))
        ));
    }
    private function userExists(string $username): bool
    {
        try {
        // Пытаемся получить пользователя из репозитория
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            return false;
        }
        return true;
    }
}