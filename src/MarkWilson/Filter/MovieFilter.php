<?php

namespace MarkWilson\Filter;

/**
 * Filter data to only include movies - not TV
 *
 * @package MarkWilson\Filter
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class MovieFilter
{
    /**
     * Filter array of data
     *
     * @param array $data Data to filter
     *
     * @return array
     */
    public function filter(array $data)
    {
        return array_filter($data, array($this, 'matchesFilter'));
    }

    /**
     * Check if data matches filter or not
     *
     * @param string $data Data to check
     *
     * @return boolean
     */
    public function matchesFilter($data)
    {
        // contains an episode reference
        if (preg_match('/^[^\(]+\([^\)]+\)\s+\{/', $data)) {
            return false;
        }

        // contains (TV)
        if (preg_match('/^[^\(]+\([^\)]+\)\s+\(T?V\)/', $data)) {
            return false;
        }

        return true;
    }
}
