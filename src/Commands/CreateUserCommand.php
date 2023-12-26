<?php

namespace Ember\Commands;

use Ember\Blog\Exceptions\CommandException;
use Ember\Blog\Exceptions\UserNotFoundException;

use Ember\Person\Name;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    // Команда зависит от контракта репозитория пользователей,
    // а не от конкретной реализации
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {
    }
    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");

        $username = $arguments->get('username');
        $password = $arguments->get('password');

        if ($this->userExists($username)) {
            $this->logger->warning("User already exists: $username");
            return;
        }

        $user = User::createFrom(
            $username,
            $arguments->get('password'),
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            )
        );

        $this->usersRepository->save($user);

        $this->logger->info('User created:'. $user->uuid());
    }
    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}
