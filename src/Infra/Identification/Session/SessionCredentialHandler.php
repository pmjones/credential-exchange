<?php
namespace Infra\Identity\Session;

use Aura\Session\Session;
use Domain\User\User;
use Domain\User\UserRepository;

class SessionCredentialHandler
{
    protected $userRepository;

    protected $session;

    public function __contruct(
        UserRepository $userRepository,
        Session $session
    ) {
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    public function __invoke(SessionCredential $credential) : User
    {
        $segment = $this->session->getSegment(get_class($this));
        $userId = $segment->get('user_id');

        return $this->userRepository->getById($userId);
    }
}
