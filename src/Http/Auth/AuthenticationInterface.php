<?php
namespace Ember\Http\Auth;


use Ember\Http\Request;
use Ember\Person\User;

interface AuthenticationInterface
{
// Контракт описывает единственный метод,
// получающий пользователя из запроса
    public function user(Request $request): User;
}
