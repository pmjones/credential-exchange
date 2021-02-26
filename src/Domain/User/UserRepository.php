<?php
namespace Domain\User;

class UserRepository
{
    public function getById(int $id) : User
    {
        return new User(/* ... */);
    }

    public function getByName(string $name) : User
    {
        return new User(/* ... */);
    }
}
