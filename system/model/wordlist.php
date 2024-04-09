<?php

namespace system\model;

use system\model\trait\injectDatabase;
use system\model\trait\singleton;

class wordlist extends base
{
    use singleton;
    use injectDatabase;

    public function create($name)
    {
        $this->conn->insert('wordlist', ['name' => $name]);
        return $this->conn->id();
    }

    public function delete($wlid)
    {
        $this->conn->delete('wordlist', ['id' => $wlid]);
    }

    public function words($wlid)
    {
        return $this->conn->query("select words.word from words,wordlist,wlistWords WHERE 
wordlist.id = wlistWords.wlid AND
words.id = wlistWords.wid AND
wordlist.id = :wlid",[":wlid" => $wlid])->fetchAll();
    }

    public function wordCount($wlid)
    {
        return $this->conn->query("select count(words.word) as wc from words,wordlist,wlistWords WHERE 
wordlist.id = wlistWords.wlid AND
words.id = wlistWords.wid AND
wordlist.id = :wlid",[":wlid" => $wlid])->fetchAll();
    }

    public function all()
    {
        return $this->conn->select('wordlist',['id', 'name']);
    }
}