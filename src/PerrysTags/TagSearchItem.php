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

    public function getIsRegex()
    {
        $temp = new StringProperty($this->tagname);
        return $temp->startsWith('^') || $temp->endsWith('$');
    }

    public function isMatch($namespace, $tagname)
    {
        $temp = new StringProperty($tagname);
        return $namespace==$this->namespace &&
            (($this->getIsRegex()===true && $temp->isMatch('/'.$this->tagname.'/')) ||
            $this->tagname==$tagname);
    }

}
