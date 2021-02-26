<?php
namespace Infra\Identity\Cli;

use Domain\User\User;
use Domain\User\UserRepository;

class CliCredentialHandler
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(CliCredential $credential) : User
    {
        return $this->userRepository->getByName($credential->getUsername());
    }
}
