<?php


namespace App\Model\User\Service;


use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;

interface IResetTokenSender
{
    public function send(Email $email, ResetToken $resetToken): void;
}