<?php

namespace app\controllers;

use system\service\Helper;

class group extends base
{
    public function create()
    {
        $user = user();
        hasAccess($user, 'createGroup');
        Helper::requiredParams(['name']);
        if (!is_string($_POST['name']))
            Helper::response('name parameter should be string!', 400, 'not ok',true);
        $name = $_POST['name'];
        $gModel = \system\model\group::getInstance();
        $gid = $gModel->create($name);
        Helper::response("group { $name } with the id of { $gid } successfully created !", 200, 'ok', true);
    }

    public function delete($gid)
    {
        $user = user();
        hasAccess($user, 'deleteGroup');
        if (!is_numeric($gid))
            Helper::response('group id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('groups',['id'=>$gid]))
            Helper::response('selected group doesn\'t exist!', 400, 'not ok',true);
        $gModel = \system\model\group::getInstance();
        $gModel->delete($gid);
        Helper::response('group deleted successfully!', 200, 'ok', true);
    }

    public function addWordlist($gid, $wlid)
    {
        $user = user();
        hasAccess($user, 'addWordlistToGroup');
        if (!is_numeric($gid))
            Helper::response('group id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('groups',['id'=>$gid]))
            Helper::response('selected group doesn\'t exist!', 400, 'not ok',true);
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);
        $gModel = \system\model\group::getInstance();
        $gModel->addWordlist($gid,$wlid);
        Helper::response('wordlist successfully added to the group!', 200, 'ok', true);
    }

    public function removeWordlist($gid, $wlid)
    {
        $user = user();
        hasAccess($user, 'removeWordlistFromGroup');
        if (!is_numeric($gid))
            Helper::response('group id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('groups',['id'=>$gid]))
            Helper::response('selected group doesn\'t exist!', 400, 'not ok',true);
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);
        if (!$this->conn->has('wlistGroup',['gid' => $gid, 'wlid' => $wlid]))
            Helper::response('selected wordlist is not in the selected group!', 400, 'ok', true);
        $gModel = \system\model\group::getInstance();
        $gModel->removeWordlist($gid,$wlid);
        Helper::response('selected wordlist successfully removed from the group!', 200, 'ok', true);
    }

    public function all()
    {
        $user = user();
        hasAccess($user, 'seeAllGroups');
        $gModel = \system\model\group::getInstance();
        $all = $gModel->all();
        Helper::response($all, 200, 'ok', true);
    }

    public function wordlists($gid)
    {
        $user = user();
        hasAccess($user, 'seeWordlistsOfAGroup');
        if (!is_numeric($gid))
            Helper::response('group id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('groups',['id'=>$gid]))
            Helper::response('selected group doesn\'t exist!', 400, 'not ok',true);
        $gModel = \system\model\group::getInstance();
        $wordlists = $gModel->wordlists($gid);
        $wlists = [];
        $c = 0;
        foreach($wordlists as $wordlist){
            $wlists[$c]['id'] = $wordlist['id'];
            $wlists[$c]['name'] = $wordlist['name'];
            $c++;
        }
        Helper::response($wlists, 200, 'ok', true);
    }

    public function words($gid)
    {
        $user = user();
        hasAccess($user, 'seeWordsOfAGroup');
        if (!is_numeric($gid))
            Helper::response('group id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('groups',['id'=>$gid]))
            Helper::response('selected group doesn\'t exist!', 400, 'not ok',true);
        $gModel = \system\model\group::getInstance();
        $words = $gModel->words($gid);
        foreach($words as $word){
            echo $word['word'] . "\n";
        }
        die();
    }

    public function wordsCount($gid)
    {
        $user = user();
        hasAccess($user, 'seeGroupsWordsCount');
        if (!is_numeric($gid))
            Helper::response('group id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('groups',['id'=>$gid]))
            Helper::response('selected group doesn\'t exist!', 400, 'not ok',true);
        $gModel = \system\model\group::getInstance();
        $wc = $gModel->wordsCount($gid);
        Helper::response(['words_count' => $wc[0]['wc']],200, 'ok', true);
    }
}