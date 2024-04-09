<?php

namespace app\controllers;

use system\service\Helper;

class wordlist extends base
{
    public function create()
    {
        $user = user();
        hasAccess($user, 'createWordlist');
        Helper::requiredParams(['name']);
        if (!is_string($_POST['name']))
            Helper::response('name parameter should be string!', 400, 'not ok',true);
        $wModel = \system\model\wordlist::getInstance();
        $name = $_POST['name'];
        $wid = $wModel->create($name);
        Helper::response(['wordlist_name' => $name, 'wordlist_id' => $wid], 200, 'ok', true);
    }

    public function delete($wlid)
    {
        $user = user();
        hasAccess($user, 'deleteWordlist');
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);
        $wModel = \system\model\wordlist::getInstance();
        $wModel->delete($wlid);
        Helper::response('wordlist deleted successfully!', 200, 'ok', true);
    }

    public function words($wlid)
    {
        $user = user();
        hasAccess($user, 'seeWordlistWords');
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);
        $wModel = \system\model\wordlist::getInstance();
        $words = $wModel->words($wlid);
        foreach($words as $word){
            echo $word['word'] . "\n";
        }
        die();
    }

    public function all()
    {
        $user = user();
        hasAccess($user, 'seeAllWordlists');
        $wModel = \system\model\wordlist::getInstance();
        $wlists = $wModel->all();
        Helper::response($wlists, 200, 'ok', true);
    }

    public function info($wlid)
    {
        $user = user();
        hasAccess($user, 'seeWordlistsWordsCount');
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);
        $wModel = \system\model\wordlist::getInstance();
        $wc = $wModel->wordCount($wlid)[0]['wc'];
        $wlistGroups = $this->conn->query("SELECT `groups`.`name` FROM `wordlist`, `wlistGroup`, `groups` WHERE
`wordlist`.`id` = `wlistGroup`.`wlid` AND
`wlistGroup`.`gid` = `groups`.`id` AND
`wordlist`.`id` = :wlid",[':wlid' => $wlid])->fetchAll();
        $wlgps = [];
        $c = 0;
        foreach($wlistGroups as $wlistGroup){
            $wlgps[$c]['name'] = $wlistGroup['name'];
            $c++;
        }
        Helper::response(['words_count' => $wc, 'groups' => $wlgps], 200, 'ok', true);

    }
}