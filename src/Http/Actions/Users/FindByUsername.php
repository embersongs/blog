<?php
namespace Ember\Http\Actions\Users;

use Ember\Blog\Exceptions\UserNotFoundException;
use Ember\Http\ActionInterface;
use Ember\Http\ErrorResponse;
use Ember\Http\HttpException;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Repositories\Users\UsersRepositoryInterface;

// Класс реализует контракт действия
class FindByUsername implements ActionInterface
{
// Нам понадобится репозиторий пользователей,
// внедряем его контракт в качестве зависимости
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
// Функция, описанная в контракте
    public function handle(Request $request): Response
    {
        try {
// Пытаемся получить искомое имя пользователя из запроса
            $username = $request->query('username');
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
// возвращаем неуспешный ответ
            return new ErrorResponse($e->getMessage());
        }
// Возвращаем успешный ответ
        return new SuccessfulResponse([
            'username' => $user->username(),
            'name' => $user->name()->first() . ' ' . $user->name()->last(),
        ]);
    }
}