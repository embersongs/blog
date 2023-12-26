<?php

namespace Ember\Repositories\Users;

use Ember\Blog\Exceptions\UserNotFoundException;
use Ember\Person\Name;
use Ember\Person\User;
use Ember\Person\UUID;

interface UsersRepositoryInterface
{
    public function save(User $user): void;
    public function get(UUID $uuid): User;

    public function getByUsername(string $username): User;
}

