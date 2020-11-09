<?php


namespace App\Model\User\Entity\User;


use Webmozart\Assert\Assert;

/**
 * Class Role
 * @package App\Model\User\Entity\User
 */
class Role
{
    /**
     *
     */
    private const USER = 'ROLE_USER';
    /**
     *
     */
    private const ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    private string $name;

    /**
     * Role constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::ADMIN,
            self::USER
        ]);
        $this->name = $name;
    }

    /**
     * @return static
     */
    public static function user(): self {
        return new self(self::USER);
    }

    /**
     * @return static
     */
    public static function admin(): self {
        return new self(self::ADMIN);
    }

    /**
     * @param Role $role
     * @return bool
     */
    public function isEqual(self $role): bool {
        return $this->getName() === $role->getName();
    }

    /**
     * @return bool
     */
    public function isUser(): bool {
        return $this->name === self::USER;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->name === self::ADMIN;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

}