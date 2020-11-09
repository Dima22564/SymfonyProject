<?php


namespace App\Model\User\Entity\User;


use DateTimeImmutable;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ResetToken
 * @package App\Model\User\Entity\User
 * @ORM\Embeddable
 */
class ResetToken
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private string $token;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private DateTimeImmutable $expires;

    /**
     * ResetToken constructor.
     * @param string $token
     * @param DateTimeImmutable $expires
     */
    public function __construct(string $token, DateTimeImmutable $expires)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expires = $expires;
    }

    /**
     * @param DateTimeImmutable $date
     * @return bool
     */
    public function isExpiredTo(DateTimeImmutable $date): bool {
        return $this->expires <= $date;
    }

    /**
     * @return string
     */
    public function getToken(): string {
        return $this->token;
    }

    /**
     * @return bool
     * @internal for post load
     */
    public function isEmpty(): bool {
        return empty($this->token);
    }
}