<?php

namespace Ember\Repositories\Users;


use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Blog\Exceptions\UserNotFoundException;


class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];
    public function save(User $user): void
    {
        $this->users[] = $user;
    }
    // Заменили int на UUID
    public function get(UUID $uuid): User
    {
        foreach ($this->users as $user) {
            // Сравниваем строковые представления UUID
            if ((string)$user->uuid() === (string)$uuid) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $uuid");
    }

    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->username() === $username) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $username");
    }
}
