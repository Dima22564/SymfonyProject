<?php


namespace App\Model\User\UseCase\SignUp\Request;


use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
//use App\Model\User\Entity\User\IUserRepository;
use App\Model\User\Repository\IUserRepository;
use App\Model\User\Service\IConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\ConfirmTokenizer;
use DateTimeImmutable;

class Handler
{

    private IUserRepository $users;
    private PasswordHasher $hasher;
    private Flusher $flusher;
    private ConfirmTokenizer $tokenizer;
    private IConfirmTokenSender $sender;

    public function __construct(
        IUserRepository $users,
        PasswordHasher $hasher,
        Flusher $flusher,
        ConfirmTokenizer $tokenizer,
        IConfirmTokenSender $sender
    )
    {
        $this->hasher = $hasher;
        $this->users = $users;
        $this->flusher = $flusher;
        $this->tokenizer = $tokenizer;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            // TODO throw Exception
        }
        $user = new User(
            Id::next(),
            new DateTimeImmutable()
        );
        $user->signUpByEmail($email, $this->hasher->hash($command->password), $token = $this->tokenizer->generate());

        $this->sender->send($email, $token);
        $this->users->add($user);
        $this->flusher->flush();
    }

}