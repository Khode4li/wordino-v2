<?php

namespace system\model;

use system\model\trait\injectDatabase;
use system\model\trait\singleton;

class word extends base
{
    use singleton;
    use injectDatabase;

    public function add(string $word, $wlid)
    {
        $wid = null;
        if ($this->conn->has('words',['word' => $word]))
            $wid = $this->conn->select('words','id',['word' => $word])[0];
        else{
            $this->conn->insert('words',['word' => $word]);
            $wid = $this->conn->id();
        }
        $this->conn->insert('wlistWords', ['wlid' => $wlid, 'wid' => $wid]);
    }

    public function MultipleAdd($words, $wlid)
    {
        $wCount = count($words);
        $fixedArrays = $this->wordsFixArray($words);
        $this->conn->query($this->wordsTableQueryMaker($wCount), $fixedArrays);
        $this->conn->query($this->wlistWordsTableQueryMaker($wCount, $wlid), $fixedArrays);
    }

    public function wordsFixArray(array $words)
    {
        $ws = [];
        foreach($words as $k => $v){
            $ws[":word$k"] = $v;
        }
        return $ws;
    }

    private function wordsTableQueryMaker(int $wCount)
    {
        $query = "INSERT IGNORE INTO words (word) VALUES ";
        for ($i=0;$i<=$wCount-1;$i++)
            $query .= "(:word$i),";
        return substr($query, 0, -1);
    }

    private function wlistWordsTableQueryMaker(int $wCount, $wlid)
    {
        $query = "INSERT IGNORE INTO wlistWords (wlid, wid) SELECT '$wlid', id FROM words WHERE word IN (";
        for ($i=0;$i<=$wCount-1;$i++){
            $query .= ":word$i,";
        }
        return substr($query, 0, -1).")";
    }
}