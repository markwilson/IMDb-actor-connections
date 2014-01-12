<?php

namespace MarkWilson\Test\FileObject;

use MarkWilson\FileObject\ActorFileObject;

/**
 * Test ActorFileObject
 *
 * @package MarkWilson\Test\FileObject
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class ActorFileObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the iterator with an example file
     */
    public function testIterator()
    {
        $fileObject = new ActorFileObject(__DIR__ . '/../Resources/example.list');

        $actors = array();

        while ($fileObject->valid()) {
            $actors[] = $fileObject->current();

            $fileObject->next();
        }

        $this->assertEquals(3, count($actors));
        $this->assertInstanceOf('\MarkWilson\Model\Actor', $actors[1]);
        $this->assertEquals('Perry, Matthew (I)', $actors[1]->getName());
        $this->assertEquals(18, $actors[1]->getTitles()->count());
    }
}
