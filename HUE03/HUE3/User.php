<?php


class User
{
    public function __construct(
        private string $userId,
        private string $name
    )
    {
    }

    public function __get(string $user): mixed
    {
        if (property_exists('User', $user)) {
            return $this->{$user};
        } else throw new Exception("Attribute " . $user . " does not exist in class User!");
    }

    public function __set(string $user, mixed $newValue): void
    {
        if (property_exists('User', $user)) {
            $this->{$user};
        } else throw new Exception("Attribute " . $user . " does not exist in class User!");
    }
}