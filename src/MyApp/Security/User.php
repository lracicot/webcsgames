<?php

namespace MyApp\Security;

use Symfony\Component\Security\Core\User\User as BaseUser;

class User extends BaseUser
{
    private $bio;
    private $picture;

    public function getBio()
    {
        return $this->bio;
    }

    public function getPicture()
    {
        return $this->picture;
    }
}
