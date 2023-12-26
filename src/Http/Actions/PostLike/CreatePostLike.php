<?php

namespace Ember\Http\Actions\PostLike;

use Ember\Blog\Exceptions\PostNotFoundException;
use Ember\Blog\LikePost;
use Ember\Http\ActionInterface;
use Ember\Http\Auth\AuthException;
use Ember\Http\Auth\TokenAuthenticationInterface;
use Ember\Http\ErrorResponse;
use Ember\Http\HttpException;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Person\UUID;
use Ember\Repositories\Likes\LikesPostsRepositoryInterface;
use Ember\Repositories\Posts\PostsRepositoryInterface;

class CreatePostLike implements ActionInterface
{
    public   function __construct(
        private LikesPostsRepositoryInterface $likesRepository,
        private PostsRepositoryInterface $postRepository,
        private TokenAuthenticationInterface $authentication,
    ) {
    }


    public function handle(Request $request): Response
    {
        try {
            $author = $this->authentication->user($request);
        } catch (AuthException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        try {
            $post_uuid = $request->JsonBodyField('post_uuid');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }


        try {
            $this->postRepository->get(new UUID($post_uuid));
        } catch (PostNotFoundException $exception) {
            return new ErrorResponse($exception->getMessage());
        }



        $uuid = UUID::random();

        $like = new LikePost(
            uuid: $uuid,
            post_uuid: new UUID($postUuid),
            user_uuid: $author->uuid(),

        );

        $this->likesRepository->save($like);

        return new SuccessfulResponse(
            ['uuid' => (string)$uuid]
        );
    }
}