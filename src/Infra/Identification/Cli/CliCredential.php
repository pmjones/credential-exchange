<?php
namespace Infra\Identity\Cli;

use App\Identity\Credential;

class CliCredential implements Credential
{
    protected $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getUsername() : string
    {
        return $this->username;
    }
}
