<?php
namespace GeekBrains\LevelTwo\Http\Actions\Users;

use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

use Psr\Log\LoggerInterface;


// Класс реализует контракт действия
class FindByUsername implements ActionInterface
{
    private UsersRepositoryInterface $usersRepository;
    private LoggerInterface $logger;


    // Нам понадобится репозиторий пользователей,
    // внедряем его контракт в качестве зависимости
    public function __construct(
        UsersRepositoryInterface $usersRepository,
        LoggerInterface $logger
        ) 
    {
        $this->usersRepository = $usersRepository;
        $this->logger = $logger;
    }

// Функция, описанная в контракте
    public function handle(Request $request): Response
    {
        try {
            // Пытаемся получить искомое имя пользователя из запроса
            $username = $request->query('name');

        } catch (HttpException $e) {
            // Если в запросе нет параметра username -
            // возвращаем неуспешный ответ,
            // сообщение об ошибке берём из описания исключения
            return new ErrorResponse($e->getMessage());
        }
        try {
            // Пытаемся найти пользователя в репозитории
            $user = $this->usersRepository->getByUsername($username);

        } catch (UserNotFoundException $e) {
            // Если пользователь не найден -
            // делаем записть в лог
            $this->logger->warning("Can't find this user: $username");
            // и возвращаем неуспешный ответ
            return new ErrorResponse($e->getMessage());
        }
            // Возвращаем успешный ответ
            return new SuccessfulResponse([
            'username' => $user->getUserName(),
            'name' => $user->getName()->getFirstName() . ' ' . $user->getName()->getLastName(),
        ]);
    }
}