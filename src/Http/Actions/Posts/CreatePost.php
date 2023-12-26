<?php
namespace Ember\Http\Actions\Posts;

use Ember\Blog\Exceptions\InvalidArgumentException;
use Ember\Blog\Exceptions\UserNotFoundException;
use Ember\Blog\Post;
use Ember\Http\ActionInterface;
use Ember\Http\Auth\AuthenticationInterface;
use Ember\Http\Auth\AuthException;
use Ember\Http\Auth\TokenAuthenticationInterface;
use Ember\Http\ErrorResponse;
use Ember\Http\HttpException;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Person\UUID;
use Ember\Repositories\Posts\PostsRepositoryInterface;
use Ember\Repositories\Users\UsersRepositoryInterface;
use Psr\Log\LoggerInterface;

readonly class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $authentication,
        private LoggerInterface          $logger,

    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newPostUuid = UUID::random();
        try {
            $post = new Post(
                $newPostUuid,
                $author,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postsRepository->save($post);

        $this->logger->info("Post created: $newPostUuid");

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}
