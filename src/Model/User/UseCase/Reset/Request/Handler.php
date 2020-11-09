<?php


namespace App\Model\User\UseCase\Reset\Request;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\User;
use App\Model\User\Repository\IUserRepository;
use App\Model\User\Service\IResetTokenSender;
use App\Model\User\Service\ResetTokenizer;
use App\Model\User\UseCase\Reset\Request\Command;

class Handler
{
    private IUserRepository $users;
    private ResetTokenizer $tokenizer;
    private Flusher $flusher;
    private IResetTokenSender $sender;

    /**
     * Handler constructor.
     * @param IUserRepository $users
     * @param ResetTokenizer $tokenizer
     * @param Flusher $flusher
     * @param IResetTokenSender $sender
     */
    public function __construct(IUserRepository $users, ResetTokenizer $tokenizer, Flusher $flusher, IResetTokenSender $sender)
    {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handle(Command $command): void {
        $user = $this->users->getByEmail(new Email($command->email));

        $user->requestPasswordReset(
            $this->tokenizer->generate(),
            new \DateTimeImmutable()
        );
        $this->flusher->flush();
        $this->sender->send($user->getEmail(), $user->getResetToken());
    }
}