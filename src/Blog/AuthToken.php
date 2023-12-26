<?php

namespace Ember\Blog;
use DateTimeImmutable;
use Ember\Person\UUID;

class AuthToken
{
    public function __construct(
// Строка токена
        private string $token,
// UUID пользователя
        private UUID $userUuid,
// Срок годности
        private DateTimeImmutable $expiresOn
    ) {
    }
    public function token(): string
    {
        return $this->token;
    }
    public function userUuid(): UUID
    {
        return $this->userUuid;
    }
    public function expiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    public function __toString()
    {
        return $this->token;
    }
    public function setExpiresOn(DateTimeImmutable $expiresOn): void
    {
        $this->expiresOn = $expiresOn;
    }
}
