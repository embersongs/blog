<?php

namespace Ember\Blog;

use Ember\Person\User;
use Ember\Person\UUID;

class Post
{
    public function __construct(
        private UUID $uuid,
        private User $author,
        private string $header,
        private string $text
    ) {
    }
    public function __toString()
    {
        return $this->header . ' >>> ' . $this->text;
    }

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get the value of header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Get the value of text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the value of uuid
     */
    public function uuid()
    {
        return $this->uuid;
    }
}
