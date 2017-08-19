<?php

namespace PerrysTags;

use PerrysLambda\StringProperty;

class TagSearchItem
{

    protected $namespace;
    protected $tagname;

    public function __construct($namespace, $tagname)
    {
        $this->namespace = $namespace;
        $this->tagname = $tagname;
    }

    public function isEmpty()
    {
        return empty($this->namespace) && empty($this->tagname);
    }

    public function isRegex()
    {
        $temp = new StringProperty($this->tagname);
        return $temp->startsWith('^') || $temp->endsWith('$');
    }

    public function isMatch($namespace, $tagname)
    {
        $temp = new StringProperty($tagname);
        return ($namespace==$this->namespace || empty($this->namespace)) &&
            (($this->isRegex()===true && $temp->isMatch('/'.$this->tagname.'/')) ||
            strpos($tagname, $this->tagname)!==false);
    }

}
