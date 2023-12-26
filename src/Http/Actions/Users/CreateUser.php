<?php

namespace Ember\Http\Actions\Users;

use Ember\Http\ActionInterface;
use Ember\Http\ErrorResponse;
use Ember\Http\HttpException;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Person\Name;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Users\UsersRepositoryInterface;

class CreateUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $newUserUuid = UUID::random();

            $user = new User(
                $newUserUuid,
                new Name(
                    $request->jsonBodyField('first_name'),
                    $request->jsonBodyField('last_name')
                ),
                $request->jsonBodyField('username')
            );

        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());

        }

        $this->usersRepository->save($user);

        return new SuccessfulResponse([
            'uuid' => (string)$newUserUuid,
        ]);
    }
}