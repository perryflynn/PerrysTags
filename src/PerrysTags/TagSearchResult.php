<?php

namespace PerrysTags;

use PerrysLambda\ArrayList;

class TagSearchResult
{

    protected $tags;
    protected $patterns;

    public function __construct(ArrayList $tags, ArrayList $patterns)
    {
        $this->tags = $tags;
        $this->patterns = $patterns;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getPatterns()
    {
        return $this->patterns;
    }

}
