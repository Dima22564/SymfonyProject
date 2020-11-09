<?php


namespace App\Model\User\UseCase\Reset\Reset;


use App\Model\User\Repository\IUserRepository;
use App\Model\User\Service\IResetTokenSender;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\ResetTokenizer;
use DateTimeImmutable;

/**
 * Class Handler
 * @package App\Model\User\UseCase\Reset\Reset
 */
class Handler
{
    /**
     * @var IUserRepository
     */
    private IUserRepository $users;
    /**
     * @var PasswordHasher
     */
    private PasswordHasher $hasher;
    /**
     * @var Flusher
     */
    private Flusher $flusher;

    /**
     * Handler constructor.
     * @param IUserRepository $users
     * @param PasswordHasher $hasher
     * @param Flusher $flusher
     */
    public function __construct(IUserRepository $users, PasswordHasher $hasher, Flusher $flusher)
    {
        $this->users = $users;
        $this->hasher = $hasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void {
        if (!$user = $this->users->findByResetToken($command->token)) {
            // TODO Exception
        }
        $user->passwordReset(
            new DateTimeImmutable(),
            $this->hasher->hash($command->password)
        );

        $this->flusher->flush();
    }
}