<?php

namespace app\controllers;

use system\service\Helper;

class word extends base
{
    public function add($wlid)
    {
        $user = user();
        hasAccess($user, 'addNewWordToWordlists');
        Helper::requiredParams(['word']);
        if (!is_string($_POST['word']))
            Helper::response('word parameter must be string!', 400, 'not ok',true);
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);
        $word = $_POST['word'];
        $wModel = \system\model\word::getInstance();
        $wModel->add($word,$wlid);
        Helper::response("the word { $word } successfully added to the $wlid", 200, 'ok',true);
    }

    public function multipleAdd($wlid)
    {
        $user = user();
        hasAccess($user, 'addMultipleWordToWordlists');
        Helper::requiredParams(['words', 'separator']);
        if (!is_string($_POST['words']))
            Helper::response('word parameter must be string!', 400, 'not ok',true);
        if (!is_string($_POST['separator']))
            Helper::response('separator parameter must be string!', 400, 'not ok',true);
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);

        $separator = $_POST['separator'];
        $words = explode($separator, $_POST['words']);
        $wModel = \system\model\word::getInstance();
        $wModel->MultipleAdd($words,$wlid);
        Helper::response('words added successfully!', 200, 'ok', true);
    }

    public function fromUrl($wlid)
    {
        $user = user();
        hasAccess($user, 'addWordsToWordlistFromUrl');
        Helper::requiredParams(['url']);
        if (!is_numeric($wlid))
            Helper::response('wordlist id  parameter must be integer!', 400, 'not ok',true);
        if (!$this->conn->has('wordlist',['id'=>$wlid]))
            Helper::response('selected wordlist doesn\'t exist!', 400, 'not ok',true);

        $words = file_get_contents($_POST['url']);
        if ($words === false)
            Helper::response('something wen\'t wrong! maybe the url is not correct or maybe the host is down!', 400, 'not ok', true);

        $words = explode("\n",$words);
        $wModel = \system\model\word::getInstance();
        $wModel->MultipleAdd($words, $wlid);
        Helper::response('words added successfully!', 200, 'ok', true);
    }
}