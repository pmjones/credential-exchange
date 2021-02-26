<?php
namespace Domain\Item;

class ItemRepository
{
    public function getById(int $id) : Item
    {
        return new Item(/* ... */);
    }

    public function save(Item $item) : void
    {
        /* ... */
    }
}
