<?php


namespace App\Tests\Unit\Model\User\Entity\User\Network;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Network;
use App\Model\User\Entity\User\User;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = new User($id = Id::next(), $date = new \DateTimeImmutable());

        $user->signUpByNetwork($network = 'facebook', $identity = '00001');

        self::assertTrue($user->isActive());
        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());

        self::assertCount(1, $networks = $user->getNetworks());
        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals($network, $first->getNetwork());
        self::assertEquals($identity, $first->getIdentity());

        self::assertTrue($user->getRole()->isUser());
    }

    public function testAlready(): void
    {
        $user = new User(Id::next(), new \DateTimeImmutable());

        $user->signUpByNetwork($network = 'facebook', $identity = '00001');
        $this->expectExceptionMessage('User is already signed up!');
        $user->signUpByNetwork($network = 'facebook', $identity = '00001');
    }
}