<?php

namespace Ember\Repositories\Users;

use Ember\Person\UUID, Ember\Person\Name;
use PDO;
use PDOStatement;
use Ember\Blog\Exceptions\UserNotFoundException;

use Ember\Person\User;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }

    public function save(User $user): void
    {

        // Подготавливаем запрос
        $statement = $this->connection->prepare(
            'INSERT INTO users (
        uuid,
username,
password,
first_name,
last_name
)
VALUES (
:uuid,
:username,
:password,
:first_name,
:last_name
)
ON CONFLICT (uuid) DO UPDATE SET
first_name = :first_name,
last_name = :last_name'
        );

        $statement->execute([
            ':uuid' => (string)$user->uuid(),
            ':username' => $user->username(),
            ':password' => $user->hashedPassword(),
            ':first_name' => $user->name()->first(),
            ':last_name' => $user->name()->last(),
        ]);

        $this->logger->info("User saved: $user_uuid");
    }
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);


        return $this->getUser($statement, $uuid);
    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }
    private function getUser(PDOStatement $statement, string $username): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->logger->warning("Cannot find user: $username");
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }
        // Создаём объект пользователя с полем username
        return new User(
            new UUID($result['uuid']),
            $result['username'],
            $result['password'],
            new Name($result['first_name'], $result['last_name'])
        );
    }
}
