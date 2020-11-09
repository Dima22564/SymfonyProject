<?php


namespace App\Model\User\Entity\User;


use Ramsey\Uuid\Uuid;

class Network
{
    /**
     * @var string
     */
    private string $id;
    /**
     * @var User
     */
    private User $user;
    /**
     * @var string
     */
    private string $network;
    /**
     * @var string
     */
    private string $identity;

    /**
     * Network constructor.
     * @param User $user
     * @param string $network
     * @param string $identity
     */
    public function __construct(User $user, string $network, string $identity)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function isForNetwork(string $network): bool
    {
        return $this->network === $network;
    }

    /**
     * @return string
     */
    public function getNetwork(): string
    {
        return $this->network;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }


}