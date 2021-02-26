<?php
namespace Infra\Identity\Falafel;

use Domain\User\User;
use Domain\User\UserRepository;

class FalafelCredentialHandler
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(FalafelCredential $credential) : User
    {
        $username = $credential->getFrameworkUser()->getUsername();
        return $this->userRepository->getByName($username);
    }
}
