<?php
namespace Ember\Blog\UnitTests\Container;
class SomeClassWithParameter
{
// Класс с одним параметром
    public function __construct(
        private int $value
    ) {
    }
    public function value(): int
    {
        return $this->value;
    }
}