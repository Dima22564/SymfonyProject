<?php


namespace App\Model\User\UseCase\Confirm;


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

    public function handle(Command $command)
    {
        if (!$user = $this->users->findByConfirmToken($command->token)) {
            // TODO throw Exception
        }
        $user->confirmSignUp();
        $this->flusher->flush();
    }


}