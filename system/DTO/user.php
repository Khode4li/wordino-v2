<?php
namespace system\DTO;

class user
{
    public function __construct(private readonly string $username, private role $roleObj){}

    public function getUsername()
    {
        return $this->username;
    }
    public function getRoleName(): string
    {
        return $this->getRole()->getName();
    }

    public function getRole(): role
    {
        return $this->roleObj;
    }
}