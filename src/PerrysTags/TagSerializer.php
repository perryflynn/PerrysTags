<?php

namespace PerrysTags;

use PerrysLambda\Serializer\Serializer;
use PerrysLambda\IItemConverter;

class TagSerializer extends Serializer
{

    public function __construct()
    {
        $serializer = function(&$row, &$key, IItemConverter $converter)
        {
            if($row instanceof Tag)
            {
                $row = $row->getTagNamespace().Tag::NAMESPACETAGSEPARATOR.$row->getTagNameQuoted();
            }
            return true;
        };

        $deserializer = function(&$row, &$key, IItemConverter $converter)
        {
            if(is_string($row))
            {
                $matches = array();
                $result = preg_match(Tag::RGX_SPLITNAMESPACETAG, $row, $matches);
                if($result===1)
                {
                    $namespacename = strtolower(trim($matches[1]));
                    $tagname = strtolower(trim($matches[2]));
                    $row = Tag::newTag($namespacename, $tagname);
                    return true;
                }
                return false;
            }
            return true;
        };

        parent::__construct($serializer, $deserializer);
    }

}
