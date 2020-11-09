<?php


namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Model\User\Entity\User
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="users", uniqueConstraints={
 *  @ORM\UniqueConstraint(columns={"email"}),
 *  @ORM\UniqueConstraint(columns={"reset_token_token"}),
 *  @ORM\UniqueConstraint(columns={"confirm_token"}),
 * })
 */
class User
{
    private const STATUS_WAIT = 'wait';
    private const STATUS_ACTIVE = 'active';
    private const STATUS_NEW = 'new';

    /**
     * @var string
     * @ORM\Column(type="string", name="status")
     */
    private string $status;

    /**
     * @var Role
     * @ORM\Column(type="user_user_role")
     */
    private Role $role;

    /**
     * @var Id
     * @ORM\Column(type="user_user_id")
     * @ORM\Id
     */
    private Id $id;

    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", name="date")
     */
    private DateTimeImmutable $date;

    /**
     * @var Email|null
     * @ORM\Column(type="user_user_email", nullable=true)
     */
    private Email $email;

    /**
     * @var ResetToken|null
     * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private ?ResetToken $resetToken;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="password_hash")
     */
    private string $passwordHash;

    /**
     * @var Network[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\User\Entity\User\Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
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
     * @ORM\Column(type="string", nullable=true, name="confirm_token")
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
        $this->role = Role::user();
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
    private function attachNetwork(string $network, string $identity): void
    {
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
    public function requestPasswordReset(ResetToken $resetToken, DateTimeImmutable $date): void
    {
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

    /**
     * @param DateTimeImmutable $date
     * @param string $hash
     */
    public function passwordReset(DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            // TODO Exception
        }
        if ($this->resetToken->isExpiredTo($date)) {
            // TODO Exception
        }
        $this->passwordHash = $hash;
    }

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this->role;
    }

    /**
     * @param Role $role
     */
    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            // TODO Exception
        }
        $this->role = $role;
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }

}