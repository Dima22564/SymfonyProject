<?php


namespace App\Model\User\UseCase\Reset\Reset;


class Command
{
    public string $token;
    public string $password;

    /**
     * Command constructor.
     * @param string $token
     * @param string $password
     */
    public function __construct(string $token, string $password)
    {
        $this->token = $token;
        $this->password = $password;
    }
}