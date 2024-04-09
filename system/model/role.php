<?php
namespace system\model;

use system\model\trait\injectDatabase;
use system\model\trait\singleton;

class role extends base
{
    use singleton;
    public function createRoleObject(string $rID)
    {
        $name = $this->getName($rID);
        $perms = $this->getPermission($rID);
        return new \system\DTO\role($name, $perms);
    }
    private function getName(string $rID)
    {
        $name = $this->conn->select('role', 'name', ['id' => $rID]);
        if (!isset($name[0]))
            throw new \Exception("the requested role '{$rID}' doesn't exist!");
        return $name[0];
    }
    private function getPermission(string $rID)
    {
        $data = $this->conn->query("
SELECT role.name as roleName, permission.name as permName, roleHasPerm.has_access
FROM role,permission,roleHasPerm WHERE
permission.id = roleHasPerm.perm_id AND
role.id = roleHasPerm.role_id AND
role.id = :roleid",
            [":roleid" => $rID])->fetchAll();
        return $data;
    }

    private function PnameToPidMapper($dbperms, $permissions, $rid)
    {
        foreach($dbperms as $px){
            if (isset($permissions[$px['name']])){
                $pval = $permissions[$px['name']];
                unset($permissions[$px['name']]);
                $permissions[$px['id']] = $pval;
            }
        }
        $insertableData = [];
        $pcount = 0;
        foreach($permissions as $perm_id => $has_access){
            $insertableData[$pcount]['has_access'] = ($has_access) ? '1' : '0';
            $insertableData[$pcount]['perm_id'] = $perm_id;
            $insertableData[$pcount]['role_id'] = $rid;
            $pcount++;
        }
        return $insertableData;
    }
    public function addNewRole($rName, $permissions): bool
    {
        $this->conn->insert('role',['name' => $rName]);
        $rid = $this->conn->id();
        $dbp = $this->conn->select('permission','*');
        $insertableData = $this->PnameToPidMapper($dbp, $permissions, $rid);
        $q = $this->conn->insert('roleHasPerm',$insertableData);
        if ($q)
            return true;
        return false;
    }

    public function edit($rid, $permissions)
    {
        $dbp = $this->conn->select('permission','*');
        $updatableData = $this->PnameToPidMapper($dbp, $permissions, $rid);
        foreach($updatableData as $data){
            $this->conn->update('roleHasPerm',['has_access' => ($data['has_access']) ? '1' : '0'], ['perm_id' => $data['perm_id'], 'role_id' => $data['role_id']]);
        }
    }

    public function delete($rid)
    {
        $this->conn->delete('role', ['id' => $rid]);
        $this->conn->delete('roleHasPerm', ['role_id' => $rid]);
    }
}