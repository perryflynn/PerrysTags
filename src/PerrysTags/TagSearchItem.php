<?php

namespace PerrysTags;

use PerrysLambda\StringProperty;

class TagSearchItem
{

    protected $namespace;
    protected $tagname;
    protected $isregex;

    public function __construct($namespace, $tagname, $isregex)
    {
        $this->namespace = $namespace;
        $this->tagname = $tagname;
        $this->isregex = $isregex === true;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getTagname()
    {
        return $this->tagname;
    }

    public function isRegex()
    {
        return $this->isregex;
    }

    public function hasNamespace()
    {
        return !empty($this->namespace);
    }

    public function hasTagname()
    {
        return !empty($this->tagname);
    }

    public function isEmpty()
    {
        return $this->hasNamespace()===false && $this->hasTagname()===false;
    }

    public function isMatch($namespace, $tagname)
    {
        $temp = new StringProperty($tagname);
        return ($namespace==$this->namespace || empty($this->namespace)) &&
            (($this->isRegex()===true && $temp->isMatch('/'.$this->tagname.'/i')) ||
            $temp->containsI($this->tagname));
    }

}
