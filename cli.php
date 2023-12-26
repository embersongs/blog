<?php

use Ember\Commands\CreateUser;
use Ember\Commands\DeletePost;
use Ember\Commands\FakeData\PopulateDB;
use Ember\Commands\UpdateUser;
use Symfony\Component\Console\Application;


$container = require __DIR__ . '/bootstrap.php';

// Создаём объект приложения
$application = new Application();

// Перечисляем классы команд
$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class,

];
foreach ($commandsClasses as $commandClass) {
// Посредством контейнера
// создаём объект команды
    $command = $container->get($commandClass);
// Добавляем команду к приложению
    $application->add($command);
}
// Запускаем приложение
$application->run();
