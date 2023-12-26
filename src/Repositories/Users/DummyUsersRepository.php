<?php

namespace Ember\Repositories\Users;

use Ember\Blog\Exceptions\UserNotFoundException;
use Ember\Person\Name;
use Ember\Person\User;
use Ember\Person\UUID;


class DummyUsersRepository implements UsersRepositoryInterface
{
    public function save(User $user): void
    {
        // Ничего не делаем
    }
    public function get(UUID $uuid): User
    {
        // И здесь ничего не делаем
        throw new UserNotFoundException("Not found");
    }
    public function getByUsername(string $username): User
    {
        // Нас интересует реализация только этого метода
        // Для нашего теста не важно, что это будет за пользователь,
        // поэтому возвращаем совершенно произвольного
        return new User(UUID::random(), "user123", new Name("first", "last"));
    }
}