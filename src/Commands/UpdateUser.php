<?php
namespace Ember\Commands;

use Ember\Person\Name;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Users\UsersRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class UpdateUser extends Command
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('users:update')
            ->setDescription('Updates a user')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a user to update'
            )
            ->addOption(
// Имя опции
                'first-name',
// Сокращённое имя
                'f',
// Опция имеет значения
                InputOption::VALUE_OPTIONAL,
// Описание
                'First name',
            )
            ->addOption(
                'last-name',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Last name',
            );
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
// Получаем значения опций
        $firstName = $input->getOption('first-name');
        $lastName = $input->getOption('last-name');
// Выходим, если обе опции пусты
        if (empty($firstName) && empty($lastName)) {
            $output->writeln('Nothing to update');
            return Command::SUCCESS;
        }
// Получаем UUID из аргумента
        $uuid = new UUID($input->getArgument('uuid'));
// Получаем пользователя из репозитория
        $user = $this->usersRepository->get($uuid);
// Создаём объект обновлённого имени
        $updatedName = new Name(
            firstName: empty($firstName)
                ? $user->name()->first() : $firstName,
            lastName: empty($lastName)
                ? $user->name()->last() : $lastName,
        );
// Создаём новый объект пользователя
        $updatedUser = new User(
            uuid: $uuid,
// Имя пользователя и пароль
// оставляем без изменений
            username: $user->username(),
            hashedPassword: $user->hashedPassword(),
// Обновлённое имя
            name: $updatedName
        );
// Сохраняем обновлённого пользователя
        $this->usersRepository->save($updatedUser);
        $output->writeln("User updated: $uuid");
        return Command::SUCCESS;
    }
}
