<?php

namespace PerrysTags;

use PerrysLambda\ObjectArray;

class Tag extends ObjectArray
{

    const RGX_SPLITNAMESPACETAG = '/^([^:]+?)\:(.+)$/';
    const NAMESPACETAGSEPARATOR = ':';
    const QUOTECHAR = '"';
    const FIELDNAME_NAMESPACE = 'tagnamespace';
    const FIELDNAME_TAGNAME = 'tagname';

    public static function newTag($namespace, $tagname)
    {
        return new self(array(
            self::FIELDNAME_NAMESPACE => $namespace,
            self::FIELDNAME_TAGNAME => $tagname,
        ));
    }

    public function getTagNamespace()
    {
        return $this->get(self::FIELDNAME_NAMESPACE);
    }

    public function getTagName()
    {
        return $this->get(self::FIELDNAME_TAGNAME);
    }

    public function getTagNameQuoted()
    {
        $name = $this->getTagName();
        if(preg_match('/\s/', $name)===1)
        {
            $name = self::QUOTECHAR.$name.self::QUOTECHAR;
        }
        return $name;
    }

    public function toString()
    {
        return $this->serialize();
    }

}
