<?php

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

    protected function getCollection()
    {
        $collection = new TagCollection($this->getTagList());
        return $collection->tagDistict();
    }

    public function testBase()
    {
        $cleancol = $this->getCollection();

        $ns = array('language', 'country', 'color', 'condition');
        $this->assertEquals($ns, $cleancol->getTagNamespaces()->toArray());

        $expcountries = array('germany', 'united states of america', 'hungary', 'italy', 'spain', 'netherlands');
        $countries = $cleancol->getTagsByNamespace('country')->getTagNames()->toArray();
        $this->assertEquals($expcountries, $countries);

        $colorswithe = $cleancol->tagSearch('color~:e$');
        $this->assertSame(array('blue', 'white', 'light orange'), $colorswithe->getTags()->getTagNames()->toArray());

        $blueamerica = $cleancol->tagSearch('color:blue      country:"united states of america"');

        $blueamser = $blueamerica->getTags()->select('toString')->joinString();
        $this->assertSame('country:"united states of america", color:blue', $blueamser);
    }

    public function testSeachPattern()
    {
        $collection = $this->getCollection();

        $this->assertSame(array("hello world"), $collection->tagSearch('"hello world"')->getPatterns()->toArray());
        $this->assertSame(array("german", "hello world"), $collection->tagSearch('german "hello world" color:white color~:^re')->getPatterns()->toArray());

        $this->assertSame(array(), $collection->tagSearch('"hello world"')->getTags()->serialize());
        $this->assertEquals(array('language:german', 'country:germany', 'color:red', 'color:white'), $collection->tagSearch('german color:white color~:^re')->getTags()->serialize());
        $this->assertEquals(array('language:german', 'country:germany', 'color:red', 'color:white'), $collection->tagSearch('GeRmAn color:whiTE color~:^RE')->getTags()->serialize());
        $this->assertEquals(array('condition:"slightly used"', 'condition:used'), $collection->tagSearch('~:used$')->getTags()->serialize());
    }

}
