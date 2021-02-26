<?php
namespace App\Identification;

use Domain\User\User;

interface CredentialExchange
{
    public function getUser(Credential $credential) : User;
}
