<?php


namespace App\Model\User\Factory;


use App\Model\User\Entity\User\User;

interface IFactory
{
    public static function getUser(): User;
}