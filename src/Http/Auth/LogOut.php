<?php

namespace Ember\Http\Auth;

use Ember\Http\ActionInterface;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Repositories\AuthTokensRepository\AuthTokenNotFoundException;
use Ember\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use Ember\Blog\AuthToken;
class LogOut implements ActionInterface
{

    public function __construct(
        private AuthTokensRepositoryInterface $authTokensRepository,
        private BearerTokenAuthentication $authentication
    ) {
    }

    /**
     * @throws AuthException
     */
    public function handle(Request $request): Response
    {
        $token = $this->authentication->getAuthTokenString($request);

        try {
            $authToken = $this->authTokensRepository->get($token);
        } catch (AuthTokenNotFoundException $exception) {
            throw new AuthException($exception->getMessage());
        }

        $authToken->setExpiresOn(new \DateTimeImmutable("now"));


        $this->authTokensRepository->save($authToken);

        return new SuccessfulResponse([
            'token' => $authToken->token()
        ]);

    }
}