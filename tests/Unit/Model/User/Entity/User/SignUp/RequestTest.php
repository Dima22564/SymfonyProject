<?php


namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Tests\Builder\User\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use App\Model\User\Entity\User\User;


class RequestTest extends TestCase
{
    public function testSuccess(): void {
        $user = new User($id = Id::next(), $date = new \DateTimeImmutable());
        $user->signUpByEmail(
            $email = new Email('test@mail.ru'),
            $hash = 'hash',
            $token = 'token'
        );

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertEquals($id, $user->getId());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($token, $user->getConfirmToken());
    }
}