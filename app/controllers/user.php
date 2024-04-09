<?php

namespace app\controllers;

use system\registry\registry;
use system\service\authenticate\auth;
use system\service\Helper;

class user extends base
{
    public function login()
    {
        Helper::requiredParams(['username', 'password']);
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $auth = auth::getInstance();
        $token = $auth->login($user, $pass);
        if ($token === false)
            Helper::response('Username or Password is wrong!',401,'not ok',true);
        Helper::response(['token' => $token],200, 'ok',true);
    }

    public function check()
    {
        $user = registry::get('user');
        Helper::response([
            'username' => $user->getUsername(),
            'role_name' => $user->getRoleName(),
            'permissions' => $user->getRole()->getAllPermissions()
        ],200,'ok',true);
    }

    public function new()
    {
        $user = user();
        hasAccess($user, 'addNewUser');
        Helper::requiredParams(['username', 'password', 'rid']); //rid is "role id"
        $username = $_POST['username'];
        $password = $_POST['password'];
        $rid = $_POST['rid'];
        if (!$this->conn->has('role', ['id' => $rid]) || $this->conn->has('user', ['username' => $username]))
            Helper::response('username exit, or role id doesn\'nt exist :D. you have to figure it out XD',409,'not ok',true);

        $uModel = \system\model\user::getInstance();
        $q = $uModel->register($username, $password, $rid);
        if (!$q)
            Helper::response('something went wrong! try again later.',500,'not ok',true);
        Helper::response('user registered successfully!',200,'ok',true);
    }

    public function all()
    {
        $user = user();
        hasAccess($user, 'seeAllUsers');
        $us = $this->conn->query("SELECT user.id as id, user.username, role.name as role FROM role, user
WHERE user.role_id = role.id")->fetchAll();
        $users = [];
        $c = 0;
        foreach($us as $user){
            $users[$c]['id'] = $user['id'];
            $users[$c]['username'] = $user['username'];
            $users[$c]['role'] = $user['role'];
            $c++;
        }
        Helper::response($users,200,'ok',true);
    }

    public function me()
    {
        $user = user();
        $uinfo = ['username' => $user->getUsername(), 'roleName' => $user->getRoleName(), 'permissions' => $user->getRole()->getAllPermissions()];
        Helper::response($uinfo,200,'ok',true);
    }

    public function changeRole($uid, $rid)
    {
        $user = user();
        hasAccess($user, 'changeOtherUsersRole');
        if (!is_numeric($rid))
            Helper::response('role id parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('role',['id'=>$rid]))
            Helper::response('selected role doesn\'t exist!', 400, 'not ok',true);
        if (!is_numeric($uid))
            Helper::response('user id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('user',['id'=>$uid]))
            Helper::response('selected user doesn\'t exist!', 400, 'not ok',true);
        $this->conn->update('user',['role_id' => $rid],['id' => $uid]);
        Helper::response("you successfully changed the user {$uid} role to {$rid}",200, 'ok', true);
    }

    public function userPermissions($uid)
    {
        $user = user();
        hasAccess($user, 'seeOtherUsersPermissions');
        if (!is_numeric($uid))
            Helper::response('user id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('user',['id'=>$uid]))
            Helper::response('selected user doesn\'t exist!', 400, 'not ok',true);
        $ps = $this->conn->query("SELECT permission.name as name, roleHasPerm.has_access as has_access FROM user,role,permission,roleHasPerm WHERE
user.role_id = role.id AND
role.id = roleHasPerm.role_id AND
permission.id = roleHasPerm.perm_id AND
user.id = :uid",[":uid" => $uid])->fetchAll();
        $perms = [];
        foreach($ps as $perm){
            $perms[$perm['name']] = $perm['has_access'];
        }
        Helper::response($perms,200, 'ok', true);
    }

    public function delete($uid)
    {
        $user = user();
        hasAccess($user, 'deleteOtherUsersAccount');
        if (!is_numeric($uid))
            Helper::response('user id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('user',['id'=>$uid]))
            Helper::response('selected user doesn\'t exist!', 400, 'not ok',true);
        $uModel = \system\model\user::getInstance();
        $uModel->deleteUserAccount($uid);
        Helper::response('user deleted successfully!', 200, 'ok',true);
    }
}