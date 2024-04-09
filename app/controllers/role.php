<?php

namespace app\controllers;

use app\controllers\base;
use system\service\Helper;
use system\service\permissionSyncer\perms;
use system\service\permissionSyncer\syncer;

class role extends base
{
    public function all()
    {
        $user = user();
        hasAccess($user, 'seeAllRoles');
        $q = $this->conn->query('SELECT role.id as rid, role.name as roleName, permission.name as permission, roleHasPerm.has_access as hasAccess
FROM role, permission, roleHasPerm
WHERE role.id = roleHasPerm.role_id
AND permission.id = roleHasPerm.perm_id
')->fetchAll();
        $data = [];
        foreach ($q as $rp){
            $data['roles'][$rp['roleName']]['id'] = $rp['rid'];
            $data['roles'][$rp['roleName']]['permission'][$rp['permission']] = ($rp['hasAccess'] === 1 || $rp['hasAccess'] === '1');
        }
        Helper::response($data,200,'ok',true);
    }

    public function allPerms()
    {
        $user = user();
        hasAccess($user, 'seeAllPerms');
        Helper::response(['permissions' => perms::DEFAULT],200,'ok',true);
    }

    public function new()
    {
        $user = user();
        hasAccess($user, 'createNewRole');
        Helper::requiredParams(['roleName', 'permissions']);
        if (!isJson($_POST['permissions']))
            Helper::response('permissions parameter should be valid json!', 400, 'not ok',true);
        $rName = $_POST['roleName'];
        $perms = json_decode($_POST['permissions'],true); //the json may have some invalid data types
        $clearPerms = $this->permissionCleaner($perms);        ///clearing invalid data :D

        $syncedPerms = syncer::sync($clearPerms);
        $rModel = \system\model\role::getInstance();
        $q = $rModel->addNewRole($rName, $syncedPerms);
        if (!$q)
            Helper::response('inserting new role failed! try again later.',500, 'not ok', true);
        Helper::response('new role created successfully',201, 'ok', true);
    }

    public function edit($rid)
    {
        $user = user();
        hasAccess($user, 'editRolePermissions');
        Helper::requiredParams(['permissions']);
        if (!is_numeric($rid))
            Helper::response('role id \'/role/edit/{rid}\' parameter must be integer!', 400, 'not ok',true);
        if (!isJson($_POST['permissions']))
            Helper::response('permissions parameter must be valid json!', 400, 'not ok',true);
        if (!$this->conn->has('role',['id'=>$rid]))
            Helper::response('selected role doesn\'t exist!', 400, 'not ok',true);


        $perms = json_decode($_POST['permissions'],true);
        $clearPerms = $this->permissionCleaner($perms);
        $cleanPerms = syncer::clean($clearPerms);

        $rModel = \system\model\role::getInstance();
        $rModel->edit($rid, $cleanPerms);
        Helper::response('role permissions updated successfully!', 200, 'ok',true);
    }

    public function delete($rid)
    {
        $user = user();
        hasAccess($user, 'deleteRolePermissions');
        if (!is_numeric($rid))
            Helper::response('role id \'/role/edit/{rid}\' parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('role',['id'=>$rid]))
            Helper::response('selected role doesn\'t exist!', 400, 'not ok',true);

        $rModel = \system\model\role::getInstance();
        $rModel->delete($rid);
        Helper::response('role deleted successfully!', 200, 'ok',true);
    }

    private function permissionCleaner($perms){
        $clearPerms = [];
        foreach($perms as $permKey => $permValue){
            if (is_bool($permValue))
                $clearPerms[$permKey] = $permValue;
        }
        return $clearPerms;
    }
}