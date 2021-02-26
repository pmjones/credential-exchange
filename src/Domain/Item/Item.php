<?php
namespace Domain\Item;

class Item
{
    public function setBody(string $body) : void
    {
        /* ... */
    }

    public function isEditableBy(User $user)
    {
        return $this->user_id === $user->user_id;
    }
}
