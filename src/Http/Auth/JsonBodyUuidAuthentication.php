<?php
namespace Ember\Http\Auth;

use Ember\Blog\Exceptions\InvalidArgumentException;
use Ember\Blog\Exceptions\UserNotFoundException;
use Ember\Http\HttpException;
use Ember\Http\Request;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Users\UsersRepositoryInterface;

class JsonBodyUuidAuthentication implements AuthenticationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
    public function user(Request $request): User
    {
        try {
// Получаем UUID пользователя из JSON-тела запроса;
// ожидаем, что корректный UUID находится в поле user_uuid
            $username = $request->jsonBodyField('username');
        } catch (HttpException $e) {
// Если невозможно получить UUID из запроса -
// бросаем исключение
            throw new AuthException($e->getMessage());
        }
        try {
// Ищем пользователя в репозитории и возвращаем его
            return $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
// Если пользователь с таким UUID не найден -
// бросаем исключение
            throw new AuthException($e->getMessage());
        }
    }
}
