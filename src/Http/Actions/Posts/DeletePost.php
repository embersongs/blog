<?php

namespace Ember\Http\Actions\Posts;

use Ember\Blog\Exceptions\PostNotFoundException;
use Ember\Http\ActionInterface;
use Ember\Http\ErrorResponse;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Person\UUID;
use Ember\Repositories\Posts\PostsRepositoryInterface;

readonly class DeletePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    )
    {
    }


    public function handle(Request $request): Response
    {
        try {
            $postUuid = $request->query('uuid');
            $this->postsRepository->get(new UUID($postUuid));

        } catch (PostNotFoundException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $this->postsRepository->delete(new UUID($postUuid));

        return new SuccessfulResponse([
            'uuid' => $postUuid,
        ]);
    }
}