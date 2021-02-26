<?php
namespace App\UseCase\Item;

use App\Payload;
use App\Identification\Credential;
use App\Identification\CredentialExchange;
use Domain\Item\ItemRepository;

class EditItem
{
    protected $credentialExchange;

    protected $itemRepository;

    protected $activeUser;

    public function __construct(
        CredentialExchange $credentialExchange,
        ItemRepository $itemRepository
    ) {
        $this->credentialExchange = $credentialExchange;
        $this->itemRepository = $itemRepository;
    }

    public function __invoke(
        Credential $credential,
        int $id,
        string $body
    ) : Payload
    {
        $this->activeUser = $this->credentialExchange->getUser($credential);

        if ($this->activeUser->isAnonymous()) {
            return Payload::unauthorized(['reason' => 'anonymous']);
        }

        $item = $this->itemRepository->getById($id);

        if (! $item->isEditableBy($this->activeUser)) {
            return Payload::unauthorized(['reason' => 'not_owner']);
        }

        $item->setBody($body);
        $this->itemRepository->save($item);

        return Payload::updated(['item' => $item]);
    }
}
