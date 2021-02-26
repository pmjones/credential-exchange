<?php
namespace Http\Action\Item;

use App\Identification\Credential;
use App\UseCase\Item\EditItem;
use Http\Responder;
use Infra\Identification\SessionCredential;
use SapiRequest;

class PostItem
{
    public function __construct(
        SapiRequest $request,
        EditItem $domain,
        Responder $Responder
    ) {
        $this->request = $request;
        $this->domain = $domain;
        $this->responder = $responder;
    }

    public function __invoke(int $id)
    {
        $credential = $this->newCredential();
        $body = $this->request->input['body'] ?? '';
        $payload = ($this->domain)($credential, $id, $body);

        return $this->responder->respond($this->request, $payload);
    }

    protected function newCredential() : Credential
    {
        return new SessionCredential();
    }
}
