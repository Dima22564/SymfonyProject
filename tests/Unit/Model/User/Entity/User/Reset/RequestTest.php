<?php


namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;
use App\Tests\Builder\User\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());
    }

    public function testAlready(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Reset token has been send!');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpires(): void
    {
        $now = new DateTimeImmutable();
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $token1 = new ResetToken('token', $now->modify('+1 day'));
        $user->requestPasswordReset($token1, $now);
        self::assertEquals($token1, $user->getResetToken());

        $token2 = new ResetToken('token', $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));
        self::assertEquals($token1, $user->getResetToken());

    }

    public function testWithoutEmail(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $this->expectExceptionMessage('Email does not specify');
        $user->requestPasswordReset($token, $now);
    }

    public function testNotConfirmed(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new UserBuilder())->viaEmail()->build();
        $this->expectExceptionMessage('User is not active');
        $user->requestPasswordReset($token, $now);
    }
}