<?php
namespace GeekBrains\LevelTwo\Http\Auth;

use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Auth\IdentificationInterface;
use Psr\Log\InvalidArgumentException;
use GeekBrains\LevelTwo\Http\ErrorResponse;



class JsonBodyUuidIdentification implements IdentificationInterface
{
    private UsersRepositoryInterface $usersRepository;

    public function __construct(
        UsersRepositoryInterface $usersRepository
        ) {
            $this->usersRepository = $usersRepository;
        }

        public function user(Request $request): User
        {
            try {
                // Получаем UUID пользователя из JSON-тела запроса;
                // ожидаем, что корректный UUID находится в поле user_uuid
                $userUuid = new UUID($request->jsonBodyField('user_uuid'));
            } catch (HttpException|InvalidArgumentException $e) {
                // Если невозможно получить UUID из запроса -
                // бросаем исключение
                throw new AuthException($e->getMessage());
            }

            try {
                // Ищем пользователя в репозитории и возвращаем его
                return $this->usersRepository->get($userUuid);
            } catch (UserNotFoundException $e) {
                // Если пользователь с таким UUID не найден -
                // бросаем исключение
                throw new AuthException($e->getMessage());
            }
        }

}

