<?php

namespace Ember\Person;


class Name
{
    public function __construct(
        private string $firstName,
        private string $lastName
    ) {
    }
    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Get the value of firstName
     */
    public function first()
    {
        return $this->firstName;
    }

    /**
     * Get the value of lastName
     */
    public function last()
    {
        return $this->lastName;
    }
}
