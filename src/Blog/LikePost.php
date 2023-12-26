<?php

namespace Ember\Blog;


use Ember\Person\UUID;

class LikePost
{
    public function __construct(
        private UUID $uuid,
        private UUID $post_uuid,
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
    public function getPostUuid(): UUID
    {
        return $this->post_uuid;
    }

    /**
     * @param UUID $post_uuid
     */
    public function setPostUuid(UUID $post_uuid): void
    {
        $this->post_uuid = $post_uuid;
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
