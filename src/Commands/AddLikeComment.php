<?php

namespace Ember\Commands;

use Ember\Blog\LikeComment;
use Ember\Person\UUID;
use Ember\Repositories\Likes\LikesCommentsRepositoryInterface;


readonly class AddLikeComment
{
    public function __construct(
        private LikesCommentsRepositoryInterface $likesRepository
    ) {
    }

    public function handle($comment_uuid, $user_uuid): void
    {


        $this->likesRepository->save(new LikeComment(
            UUID::random(),
            new UUID($comment_uuid),
            new UUID($user_uuid)
        ));
    }
}
