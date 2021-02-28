<?php
namespace Infra\Identity\Oauth2Token;

use Domain\User\User;
use Domain\User\UserRepository;
use Infra\Token\TokenService;

class OAuth2TokenCredentialHandler
{
    protected $userRepository;

    public function __construct(
        TokenService $tokenService,
        UserRepository $userRepository
    )
    {
        $this->tokenService = $tokenService;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Oauth2TokenCredential $credential) : User
    {
        $username = $this->tokenService->getUsername($credential->getToken());
        return $this->userRepository->getByName($credential->getUsername());
    }
}
