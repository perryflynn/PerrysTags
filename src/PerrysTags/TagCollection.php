<?php

namespace PerrysTags;

use PerrysLambda\ArrayList;
use PerrysLambda\IListConverter;

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
        else if($data instanceof IListConverter)
        {
            parent::__construct($data);
        }
        else
        {
            parent::__construct();
        }
    }

    public function addRange(array $data)
    {
        foreach($data as $item)
        {
            $this->add($item);
        }
    }

    public function getIsValidValue($value)
    {
        return $value instanceof Tag;
    }

    /**
     * Cleanup duplicates in the collection
     * @return \PerrysTags\TagCollection
     */
    public function tagDistict()
    {
        return $this->distinct('toString');
    }

    /**
     * Get all namespaces
     * @return \PerrysLambda\ArrayList
     */
    public function getTagNamespaces()
    {
        return $this->select(Tag::FIELDNAME_NAMESPACE)->distinct();
    }

    /**
     * Get all tag names
     * @return \PerrysLambda\ArrayList
     */
    public function getTagNames()
    {
        return $this->select(Tag::FIELDNAME_TAGNAME)->distinct();
    }

    /**
     * Search for tags
     * @param string $pattern
     * @return \PerrysTags\TagCollection
     */
    public function tagSearch($pattern)
    {
        $search = new TagSearch($pattern);
        $tags =  $this->where(function(Tag $tag) use($search)
        {
            return $search->getIsMatch($tag)===true;
        });

        return new TagSearchResult($tags, $search->getNoNamespaceItems()->select('getTagname'));
    }

    /**
     * Get all tags by namespace
     * @param string $namespace
     * @return \PerrysTags\TagCollection
     */
    public function getTagsByNamespace($namespace)
    {
        return $this->where(function(Tag $tag) use($namespace)
        {
            return $tag->getTagNamespace()==$namespace;
        });
    }

}
