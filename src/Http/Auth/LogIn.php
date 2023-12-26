<?php
namespace Ember\Http\Auth;

use DateTimeImmutable;
use Ember\Blog\AuthToken;
use Ember\Http\ActionInterface;
use Ember\Http\Auth\AuthException;
use Ember\Http\Auth\PasswordAuthenticationInterface;
use Ember\Http\ErrorResponse;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;

class LogIn implements ActionInterface
{
    public function __construct(
// Авторизация по паролю
        private PasswordAuthenticationInterface $passwordAuthentication,
// Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }
    public function handle(Request $request): Response
    {
// Аутентифицируем пользователя
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }
// Генерируем токен
        $authToken = new AuthToken(
// Случайная строка длиной 40 символов
            bin2hex(random_bytes(40)),
            $user->uuid(),
// Срок годности - 1 день
            (new DateTimeImmutable())->modify('+1 day')
        );
// Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
// Возвращаем токен
        return new SuccessfulResponse([
            'token' => (string)$authToken,
        ]);
    }
}