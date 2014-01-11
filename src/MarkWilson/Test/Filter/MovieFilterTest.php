<?php

namespace MarkWilson\Test\Filter;

use MarkWilson\Filter\MovieFilter;

/**
 * Tests MovieFilter
 *
 * @package MarkWilson\Test\Filter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class MovieFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests filter()
     */
    public function testArrayFilter()
    {
        $filter = new MovieFilter();
        $filteredData = $filter->filter($this->getAllData());

        $this->assertEquals($this->getValidData(), $filteredData);
    }

    /**
     * Tests matchesFilter()
     *
     * @param mixed $data Data to test
     *
     * @dataProvider validFilterDataProvider
     */
    public function testValidData($data)
    {
        $filter = new MovieFilter();
        $this->assertTrue($filter->matchesFilter($data));
    }

    /**
     * Tests matchesFilter()
     *
     * @param mixed $data Data to test
     *
     * @dataProvider invalidFilterDataProvider
     */
    public function testInvalidData($data)
    {
        $filter = new MovieFilter();
        $this->assertFalse($filter->matchesFilter($data));
    }

    /**
     * Get valid filter data
     *
     * @return array
     */
    public function validFilterDataProvider()
    {
        return array_map(array($this, 'convertToArray'), $this->getValidData());
    }

    /**
     * Get invalid filter data
     *
     * @return array
     */
    public function invalidFilterDataProvider()
    {
        return array_map(array($this, 'convertToArray'), $this->getInvalidData());
    }

    /**
     * Get all data
     *
     * @return array
     */
    private function getAllData()
    {
        return array_merge($this->getValidData(), $this->getInvalidData());
    }

    /**
     * Get valid data
     *
     * @return array
     */
    private function getValidData()
    {
        return array(
            'Valid'
        );
    }

    /**
     * Get invalid data
     *
     * @return array
     */
    private function getInvalidData()
    {
        return array(
            '15th Annual Critics\' Choice Movie Awards (2010) (TV)  [Himself]',
            'American Empire (2011) {{SUSPENDED}}',
            'Footloose: A Modern Musical (2004) (V)  [Himself]',
            '"American Gangster" (2006) {Felix Mitchell (#2.6)}  (as Too Short)  [Himself]',
            'Testing (' . (date('Y') + 1) . ') [Himself]'
        );
    }

    /**
     * Convert data for provider function
     *
     * @param mixed $data Data to convert
     *
     * @return array
     */
    private function convertToArray($data)
    {
        return array($data);
    }
}
