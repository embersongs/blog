<?php

use Dotenv\Dotenv;
use Faker\Provider\ru_RU\Internet;
use Faker\Provider\Lorem;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\ru_RU\Text;
use Ember\Http\Auth\AuthenticationInterface;
use Ember\Http\Auth\BearerTokenAuthentication;
use Ember\Http\Auth\JsonBodyUuidAuthentication;
use Ember\Http\Auth\PasswordAuthentication;
use Ember\Http\Auth\PasswordAuthenticationInterface;
use Ember\Http\Auth\TokenAuthenticationInterface;
use Ember\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use Ember\Repositories\AuthTokensRepository\SqliteAuthTokensRepository;
use Ember\Repositories\Comments\CommentsRepositoryInterface;
use Ember\Repositories\Comments\SqliteCommentsRepository;
use Ember\Repositories\Likes\LikesCommentsRepositoryInterface;
use Ember\Repositories\Likes\LikesPostsRepositoryInterface;
use Ember\Repositories\Likes\SqliteLikesCommentsRepository;
use Ember\Repositories\Likes\SqliteLikesPostsRepository;
use Ember\Repositories\Posts\PostsRepositoryInterface;
use Ember\Repositories\Posts\SqlitePostsRepository;
use Ember\Repositories\Users\SqliteUsersRepository;
use Ember\Repositories\Users\UsersRepositoryInterface;
use Ember\Container\DIContainer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

// Подключаем автозагрузчик Composer
require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

// Создаём объект контейнера ..
$container = new DIContainer();
// .. и настраиваем его:
// 1. подключение к БД
$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])
);

$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

$container->bind(
    CommentsRepositoryInterface::class,
    SqliteCommentsRepository::class
);

$container->bind(
    LikesPostsRepositoryInterface::class,
    SqliteLikesPostsRepository::class
);

$container->bind(
    LikesCommentsRepositoryInterface::class,
    SqliteLikesCommentsRepository::class
);

$container->bind(
    AuthenticationInterface::class,
    JsonBodyUuidAuthentication::class
);


// Выносим объект логгера в переменную
$logger = (new Logger('blog'));
// Включаем логирование в файлы,
// если переменная окружения LOG_TO_FILES
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}
// Включаем логирование в консоль,
// если переменная окружения LOG_TO_CONSOLE
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
        ->pushHandler(
            new StreamHandler("php://stdout")
        );
}
$container->bind(
    LoggerInterface::class,
    $logger
);

$container->bind(
    AuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);
$container->bind(
    AuthTokensRepositoryInterface::class,
    SqliteAuthTokensRepository::class
);

$container->bind(
    TokenAuthenticationInterface::class,
    BearerTokenAuthentication::class
);

// Создаём объект генератора тестовых данных
$faker = new \Faker\Generator();
// Инициализируем необходимые нам виды данных
$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));
// Добавляем генератор тестовых данных
// в контейнер внедрения зависимостей
$container->bind(
    \Faker\Generator::class,
    $faker
);


return $container;
