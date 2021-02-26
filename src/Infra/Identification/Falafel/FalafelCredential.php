<?php
namespace Infra\Identity\Falafel;

use App\Identity\Credential;
use Falafel\Model\User;

class FalafelCredential implements Credential
{
    protected $user;

    public function __construct(User $frameworkUser)
    {
        $this->frameworkUser = $frameworkUser;
    }

    public function getFrameworkUser() : string
    {
        return $this->frameworkUser;
    }
}
