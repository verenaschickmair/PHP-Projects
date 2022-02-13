<?php


class UserList{
    public function __construct(
        private array $users = []
    )
    {
    }

    public function __get(string $users): mixed
    {
        if (property_exists('UserList', $users)) {
            return $this->{$users};
        } else throw new Exception("Attribute " . $users . " does not exist in class User!");
    }

    public function __set(string $users, mixed $newValue): void
    {
        if (property_exists('UserList', $users)) {
            $this->{$users};
        } else throw new Exception("Attribute " . $users . " does not exist in class User!");
    }

    public function addUser(User $user){
        if (!array_key_exists($user->userId, $this->users)){
            $this->users[$user->userId] = $user;
        }
        else throw new Exception("User " . $user->userId . " already exists!</div>");
    }
}