<?php


namespace App\Model\User\Repository;


use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;

interface IUserRepository
{
    public function hasByEmail(Email $email): bool;

    public function add(User $user): void;

    public function findByConfirmToken(string $token): ?User;

    public function getByEmail(Email $email): User;

    public function findByResetToken(string $resetToken): ?User;

    public function get(Id $id): User;
}