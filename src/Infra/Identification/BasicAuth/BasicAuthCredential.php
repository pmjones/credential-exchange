<?php
namespace Infra\Identity\BasicAuth;

use App\Identity\Credential;

class BasicAuthCredential implements Credential
{
    protected $username;

    protected $password;

    public function __construct(string $header)
    {
        // strip "Basic " from the Authorization header
        $header = substr(trim($header), 6);

        // decode and split into username and password
        $decoded = base64_decode(trim($header));
        list($this->username, $this->password) = explode(':', $header);
    }

    public function getUsername() : string
    {
        return $this->username;
    }

    public function getPassword() : string
    {
        return $this->password;
    }
}
