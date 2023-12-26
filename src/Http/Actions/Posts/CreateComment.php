<?php
namespace Ember\Http\Actions\Posts;

use Ember\Blog\Comment;
use Ember\Blog\Exceptions\InvalidArgumentException;
use Ember\Blog\Exceptions\PostNotFoundException;
use Ember\Blog\Exceptions\UserNotFoundException;
use Ember\Blog\Post;
use Ember\Http\ActionInterface;
use Ember\Http\ErrorResponse;
use Ember\Http\HttpException;
use Ember\Http\Request;
use Ember\Http\Response;
use Ember\Http\SuccessfulResponse;
use Ember\Person\UUID;
use Ember\Repositories\Comments\CommentsRepositoryInterface;
use Ember\Repositories\Posts\PostsRepositoryInterface;
use Ember\Repositories\Users\UsersRepositoryInterface;

readonly class CreateComment implements ActionInterface{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
        private CommentsRepositoryInterface $commentsRepository,
    ) {
    }
    public function handle(Request $request): Response
    {
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }


        $newCommentUuid = UUID::random();
        try {
            $comment = new Comment(
                $newCommentUuid,
                $user,
                $post,
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);

        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }
}