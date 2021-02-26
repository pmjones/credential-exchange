<?php
namespace Infra\Identity;

use Psr\Container\ContainerInterface;

class CredentialExchange implements \App\Identity\CredentialExchange
{
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getUser(Credential $credential) : User
    {
        $handler = $this->newHandler($credential);
        return ($handler)($credential);
    }

    protected function newHandler(Credential $credential)
    {
        $class = get_class($credential) . 'Handler';
        return $this->container->get($class);
    }
}
