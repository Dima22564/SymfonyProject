<?php


namespace App\Model\User\UseCase\Role;


use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Role;
use App\Model\User\Repository\IUserRepository;

class Handler
{
    private IUserRepository $users;
    private Flusher $flusher;

    /**
     * Handler constructor.
     * @param IUserRepository $users
     * @param Flusher $flusher
     */
    public function __construct(IUserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command) {
        $user = $this->users->get(new Id($command->id));
        $user->changeRole(new Role($command->role));
        $this->flusher->flush();
    }


}