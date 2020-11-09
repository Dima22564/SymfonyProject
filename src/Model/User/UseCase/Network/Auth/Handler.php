<?php


namespace App\Model\User\UseCase\Network\Auth;


use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Model\User\Repository\IUserRepository;
use DateTimeImmutable;

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

    public function handle(Command $command): void {
        if ($this->users->hasByNetworkIdentity($command->network, $command->identity)) {
            // TODO Exception
        }

        $user = new User(Id::next(), new DateTimeImmutable());

        $user->signUpByNetwork($command->network, $command->identity);
        $this->users->add($user);
        $this->flusher->flush();
    }


}