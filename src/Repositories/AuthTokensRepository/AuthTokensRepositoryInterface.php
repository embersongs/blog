<?php

namespace Ember\Repositories\AuthTokensRepository;

use Ember\Blog\AuthToken;

interface AuthTokensRepositoryInterface
{
// Метод сохранения токена
    public function save(AuthToken $authToken): void;
// Метод получения токена
    public function get(string $token): AuthToken;
}
