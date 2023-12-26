<?php

namespace Ember\Blog;

use Ember\Person\User;
use Ember\Person\UUID;

class Comment
{
    public function __construct(
        private UUID $uuid,
        private User $author,
        private Post $post,
        private string $text
    ) {
    }

    public function __toString()
    {
        return $this->text;
    }

    /**
     * Get the value of uuid
     */
    public function uuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * Get the value of author
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * Get the value of post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * Get the value of text
     */
    public function getText(): string
    {
        return $this->text;
    }
}
