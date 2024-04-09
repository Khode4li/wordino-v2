<?php
namespace system\DTO;

use system\registry\registry;
use system\service\permissionSyncer\syncer;

class role
{
    private array $permissions;

    public function __construct(private readonly string $name, array $permissions)
    {
        $perms = [];
        foreach($permissions as $perm){
            $perms[$perm['permName']] = ($perm['has_access'] === '1' or $perm['has_access'] === 1);
        }
        $this->permissions = syncer::sync($perms);
    }

    public function hasAccess(string $permName)
    {
        if ($this->name === 'owner')
            return true;
        if (!isset($this->permissions[$permName]))
            throw new \Exception('permission name doesn\'nt exist!');
        return $this->permissions[$permName];
    }

    public function  getName(): string
    {
        return $this->name;
    }

    public function getAllPermissions()
    {
        return $this->permissions;
    }

}