<?php


namespace App\Model\User\UseCase\SignUp\Request;


/**
 * Class Command
 * @package App\Model\User\UseCase\SignUp\Request
 */
class Command
{
    /**
     * @var string
     */
    public string $email;


    /**
     * @var string
     */
    public string $password;
}