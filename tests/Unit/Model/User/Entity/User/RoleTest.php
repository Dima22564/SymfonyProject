<?php


namespace App\Tests\Unit\Model\User\Entity\User;

use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;
use App\Model\User\Entity\User\Role;

class RoleTest extends TestCase
{
    public function testSuccess(): void {
        $user = (new UserBuilder())->viaEmail()->build();
        $user->changeRole(Role::admin());

        self::assertFalse($user->getRole()->isUser());
        self::assertTrue($user->getRole()->isAdmin());
    }

    public function testAlready(): void {
        $user = (new UserBuilder())->viaEmail()->build();
        $this->expectExceptionMessage('Role is same!');
        $user->changeRole(Role::user());
    }
}