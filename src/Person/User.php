<?php

namespace Ember\Person;


class User
{
    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $hashedPassword,
        private Name $name
    ) {
    }
    public function __toString()
    {
        return $this->name->__toString();
    }

    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    private static function hash(string $password, UUID $uuid): string
    {
        return hash('sha256', $uuid . $password);
    }

    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }

    public static function createFrom(
        string $username,
        string $password,
        Name $name
    ): self
    {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $username,
            self::hash($password, $uuid),
            $name
        );
    }
    /**
     * Get the value of id
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * Get the value of name
     */
    public function name(): Name
    {
        return $this->name;
    }

    /**
     * Get the value of username
     */
    public function username(): string
    {
        return $this->username;
    }



    }
