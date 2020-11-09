<?php


namespace App\Model\User\Service;


use App\Model\User\Entity\User\Email;

interface IConfirmTokenSender
{
    public function send(Email $email, string $token): void;
}