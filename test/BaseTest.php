<?php

use PerrysTags\Tag;
use PerrysTags\TagCollection;

if (!class_exists('\PHPUnit\Framework\TestCase') &&
    class_exists('\PHPUnit_Framework_TestCase'))
{
    class_alias('\PHPUnit_Framework_TestCase', 'PHPUnit\Framework\TestCase');
}

class BaseTest extends \PHPUnit\Framework\TestCase
{

    protected function getTagList()
    {
        return array(
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
    }

    public function testBase()
    {
        $collection = new TagCollection();
        foreach($this->getTagList() as $tag)
        {
            $collection->add($tag);
        }

        $cleancol = $collection->tagDistict();

        $ns = array('language', 'country', 'color', 'condition');
        $this->assertEquals($ns, $cleancol->getTagNamespaces()->toArray());

        $expcountries = array('germany', 'united states of america', 'hungary', 'italy', 'spain', 'netherlands');
        $countries = $cleancol->getTagsByNamespace('country')->getTagNames()->toArray();
        $this->assertEquals($expcountries, $countries);

        $colorswithe = $cleancol->tagSearch('color:e$');
        $this->assertSame(array('blue', 'white', 'light orange'), $colorswithe->getTagNames()->toArray());

        $blueamerica = $cleancol->tagSearch('color:blue country:"united states of america"');

        $blueamser = $blueamerica->select('toString')->joinString();
        $this->assertSame('country:"united states of america", color:blue', $blueamser);
    }

}
