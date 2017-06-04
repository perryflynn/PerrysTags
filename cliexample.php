<?php

require_once __DIR__."/vendor/autoload.php";

use PerrysTags\Tag;
use PerrysTags\TagCollection;

// Example taglist, could come from sql statement
$taglist = array(
    'language:english',
    'language:german',
    'country:germany',
    'country:united states of america',
    'country:united states of america',
    'country:united states of america',
    'country:hungary',
    'country:italy',
    'country:spain',
    'country:netherlands',
    'color:green',
    'color:red',
    'color:yellow',
    'color:gray',
    'color:blue',
    'color:white',
    'color:light orange',
    'condition:slightly used',
    'condition:used',
    'condition:new',
    'condition:broken',
);

// Create tag collection
$collection = new TagCollection();

// Import tags
foreach($taglist as $tag)
{
    $collection->add($tag);
}

// Make collection unique
$cleancol = $collection->distinct(function(Tag $tag) { return $tag->toString(); });
unset($collection);

// Prints distict list of all tags
var_dump($cleancol->serialize());

// Prints list of all namespaces
var_dump($cleancol->getTagNamespaces()->toArray());

// Prints result of a search
var_dump($cleancol->tagSearch('country:"^united states" color:"e$" condition:used')->serialize());
