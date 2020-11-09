<?php


namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class User
 * @package App\Model\User\Entity\User
 */
class User
{
    /**
     *
     */
    private const STATUS_WAIT = 'wait';

    /**
     *
     */
    private const STATUS_ACTIVE = 'active';
    /**
     *
     */
    private const STATUS_NEW = 'new';

    /**
     * @var string
     */
    private string $status;

    /**
     * @var Id
     */
    private Id $id;

    /**
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $date;

    /**
     * @var Email
     */
    private Email $email;

    /**
     * @var ResetToken|null
     */
    private ?ResetToken $resetToken;

    /**
     * @var string
     */
    private string $passwordHash;

    /**
     * @var Network[]|ArrayCollection
     */
    private $networks;

    /**
     * @return string
     */
    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }

    /**
     * @return ResetToken|null
     */
    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    /**
     * @var string
     */
    private ?string $confirmToken;

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * User constructor.
     * @param Id $id
     * @param DateTimeImmutable $date
     */
    public function __construct(
        Id $id,
        DateTimeImmutable $date
    )
    {
        $this->date = $date;
        $this->id = $id;
        $this->status = self::STATUS_NEW;
        $this->networks = new ArrayCollection();
    }

    /**
     * @param Email $email
     * @param string $hash
     * @param string $token
     */
    public function signUpByEmail(Email $email, string $hash, string $token): void
    {
        if (!$this->isNew()) {
            // TODO Exception
        }
        $this->email = $email;
        $this->passwordHash = $hash;
        $this->confirmToken = $token;
        $this->status = self::STATUS_WAIT;
    }

    /**
     * @param string $network
     * @param string $identity
     */
    public function signUpByNetwork(string $network, string $identity): void
    {
        if (!$this->isNew()) {
            // TODO Exception
        }
        $this->attachNetwork($network, $identity);
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @param string $network
     * @param string $identity
     */
    private function attachNetwork(string $network, string $identity): void {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                // TODO Exception
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    /**
     * @return Network[]|ArrayCollection
     */
    public function getNetworks()
    {
        return $this->networks->toArray();
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * @return bool
     */
    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Id
     */
    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     *
     */
    public function confirmSignUp(): void
    {
        if (!$this->isWait()) {
            // TODO Exception
        }
        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    /**
     * @param ResetToken $resetToken
     * @param DateTimeImmutable $date
     */
    public function requestPasswordReset(ResetToken $resetToken, DateTimeImmutable $date): void {
        if ($this->isActive()) {
            // TODO Exception
        }
        if (!$this->email) {
            // TODO Exception
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            // TODO Exception
        }
        $this->resetToken = $resetToken;
    }

    public function passwordReset(DateTimeImmutable $date, string $hash): void {
        if (!$this->resetToken) {
            // TODO Exception
        }
        if ($this->resetToken->isExpiredTo($date)) {
            // TODO Exception
        }
        $this->passwordHash = $hash;
    }

}