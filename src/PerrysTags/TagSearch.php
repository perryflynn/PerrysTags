<?php

namespace PerrysTags;

class TagSearch
{

    const STATE_NS = 'ns';
    const STATE_TAG = 'tag';
    const MULTIWORD_CHAR = '"';
    const SEPARATOR = " ";
    const REGEXMARKER_CHAR = '~';

    protected $searchparams;

    public function __construct($searchstr)
    {
        $this->searchparams = $this->parseSearchString($searchstr);
    }

    public function getIsMatch(Tag $tag)
    {
        return $this->searchparams->any(function(TagSearchItem $item) use($tag)
        {
            return $item->isMatch($tag->getTagNamespace(), $tag->getTagName())===true;
        });
    }

    public function getItems()
    {
        return $this->searchparams;
    }

    public function getNoNamespaceItems()
    {
        return $this->getItems()->where(function(TagSearchItem $item)
        {
            return $item->hasNamespace()===false;
        });
    }

    protected function parseSearchString($str)
    {
        $result = array();

        $imultiword = false;
        $istate = null;
        $ibuffer = null;

        // Commit function
        $commit = function($ibuffer) use(&$result)
        {
            if(!empty($ibuffer['namespace']) && empty($ibuffer['tagname']))
            {
                $ibuffer['tagname'] = $ibuffer['namespace'];
                $ibuffer['namespace'] = null;
            }

            foreach($ibuffer as &$value)
            {
                if(is_string($value))
                {
                    $value = trim($value);
                    if(empty($value))
                    {
                        $value = null;
                    }
                }
            }
            unset($value);

            $result[] = $ibuffer;
        };

        // Parse string
        $char = null;
        $prevchar = null;
        $tempstr = trim($str);
        for($i=0; $i<mb_strlen($tempstr); $i++)
        {
            $char = $tempstr[$i];

            // Create new buffer
            if($istate===null)
            {
                $ibuffer = array(
                    'namespace' => '',
                    'tagname' => '',
                    'isregex' => false,
                );
                $istate = self::STATE_NS;
            }

            // Toggle multiword mode
            if($char===self::MULTIWORD_CHAR)
            {
                $imultiword = !$imultiword;
            }
            // Change state to tag name
            else if($istate===self::STATE_NS && $char===Tag::NAMESPACETAGSEPARATOR)
            {
                $istate = self::STATE_TAG;
                if($prevchar===self::REGEXMARKER_CHAR)
                {
                    $ibuffer['isregex'] = true;
                }
            }
            // Commit buffer
            else if($char===self::SEPARATOR && $imultiword===false &&
                (!empty($ibuffer['namespace']) || !empty($ibuffer['tagname'])))
            {
                $commit($ibuffer);
                $ibuffer=null;
                $istate=null;
            }
            // Append to namespace
            else if($istate===self::STATE_NS && $char!==self::REGEXMARKER_CHAR)
            {
                $ibuffer['namespace'] .= $char;
            }
            // Append to tagname
            else if($istate===self::STATE_TAG)
            {
                $ibuffer['tagname'] .= $char;
            }

            $prevchar = $char;
        }

        $commit($ibuffer);

        // Create tagsearchitem objects
        foreach($result as &$item)
        {
            $item = new TagSearchItem($item['namespace'], $item['tagname'], $item['isregex']);
        }
        unset($item);

        // Create ArrayList and filter empty items
        return (new \PerrysLambda\ArrayList($result))
            ->where(function(TagSearchItem $i) { return $i->isEmpty()===false; });
    }

}
