<?php

namespace system\model;

use system\model\trait\injectDatabase;
use system\model\trait\singleton;

class group extends base
{
    use singleton;
    use injectDatabase;

    public function create($name)
    {
        $this->conn->insert('groups', ['name' => $name]);
        return $this->conn->id();
    }

    public function delete($gid)
    {
        $this->conn->delete('groups', ['id' => $gid]);
    }

    public function addWordlist($gid, $wlid)
    {
        $this->conn->insert('wlistGroup', ['gid' => $gid, 'wlid' => $wlid]);
    }

    public function removeWordlist($gid, $wlid)
    {
        $this->conn->delete('wlistGroup',['gid' => $gid, 'wlid' => $wlid]);
    }

    public function all()
    {
        return $this->conn->select('groups', ['id', 'name']);
    }

    public function wordlists($gid)
    {
        return $this->conn->query("SELECT `wordlist`.`id`, `wordlist`.`name` FROM `wordlist`, `wlistGroup` WHERE
`wordlist`.`id` = `wlistGroup`.`wlid` AND
`wlistGroup`.`gid` = :gid",[':gid'=>$gid])->fetchAll();
    }

    public function words($gid)
    {
        return $this->conn->query("SELECT words.word FROM words, wlistWords, wlistGroup WHERE
words.id = wlistWords.wid AND
wlistWords.wlid = wlistGroup.wlid AND
wlistGroup.gid = :gid",[':gid'=>$gid])->fetchAll();
    }

    public function wordsCount($gid)
    {
        return $this->conn->query("SELECT count(words.word) as wc FROM words, wlistWords, wlistGroup WHERE
words.id = wlistWords.wid AND
wlistWords.wlid = wlistGroup.wlid AND
wlistGroup.gid = :gid",[':gid'=>$gid])->fetchAll();
    }
}