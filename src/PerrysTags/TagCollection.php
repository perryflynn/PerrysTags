<?php

namespace PerrysTags;

use PerrysLambda\ArrayList;

class TagCollection extends ArrayList
{

    public function __construct($data = array())
    {
        if(is_array($data))
        {
            $converter = new TagConverter();
            $converter->setArraySource($data);
            parent::__construct($converter);
        }
        else
        {
            parent::__construct($data);
        }
    }

    public function getIsValidValue($value)
    {
        return $value instanceof Tag;
    }

    public function getTagNamespaces()
    {
        return $this->select(Tag::FIELDNAME_NAMESPACE)->distinct();
    }

    public function tagSearch($pattern)
    {
        $search = new TagSearch($pattern);
        return $this->where(function(Tag $tag) use($search)
        {
            return $search->getIsMatch($tag)===true;
        });
    }

}
