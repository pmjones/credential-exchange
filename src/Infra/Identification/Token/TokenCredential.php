<?php
namespace Infra\Identity\Token;

use App\Identity\Credential;

class TokenCredential implements Credential
{
    protected $token;

    public function __construct(string $header)
    {
        // strip "Token " from the Authorization header
        $this->token = trim(substr(trim($header), 5));
    }

    public function getToken() : string
    {
        return $this->token;
    }
}
