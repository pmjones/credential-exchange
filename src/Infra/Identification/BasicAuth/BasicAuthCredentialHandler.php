<?php
namespace Infra\Identity\BasicAuth;

use Domain\User\User;
use Domain\User\UserRepository;

class BasicAuthCredentialHandler
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(BasicAuthCredential $credential) : User
    {
        $user = $this->userRepository->getByName($credential->getUsername());

        if (! $user->passwordMatches($data['password'])) {
            // throw
        }

        return $user;
    }
}
