<?php

namespace Ember\Blog;


use Ember\Person\UUID;

class LikeComment
{
    public function __construct(
        private UUID $uuid,
        private UUID $comment_uuid,
        private UUID $user_uuid
    ) {
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID $uuid
     */
    public function setUuid(UUID $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return UUID
     */
    public function getCommentUuid(): UUID
    {
        return $this->comment_uuid;
    }

    /**
     * @param UUID $comment_uuid
     */
    public function setCommentUuid(UUID $comment_uuid): void
    {
        $this->comment_uuid = $comment_uuid;
    }

    /**
     * @return UUID
     */
    public function getUserUuid(): UUID
    {
        return $this->user_uuid;
    }

    /**
     * @param UUID $user_uuid
     */
    public function setUserUuid(UUID $user_uuid): void
    {
        $this->user_uuid = $user_uuid;
    }


}
