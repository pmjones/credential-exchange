<?php
namespace Domain\User;

class User
{
    /* ... */

    public function isAuthentic() : bool
    {
        return $this->user_id !== null;
    }

    public function isAnonymous() : bool
    {
        return $this->user_id === null;
    }

    public function passwordMatches(string $password) : bool
    {
        return $this->password_hash === password_hash($password);
    }
}
