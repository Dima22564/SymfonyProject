<?php


namespace App\Model\User\Entity\User;


use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Email
{
    /**
     * @var string
     */
    private string $value;

    /**
     * Email constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Incorrect email!');
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

}