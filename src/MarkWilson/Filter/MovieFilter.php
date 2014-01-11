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

        // contains (TV), (V), or (VG)
        if (preg_match('/^[^\(]+\([^\)]+\)\s+\((TV|V|VG)\)/', $data)) {
            return false;
        }

        preg_match('/^[^\(]+\(([0-9]{4})\)/', $data, $matches);
        if (isset($matches[1]) && (int)$matches[1] > date('Y')) {
            return false;
        }

        return true;
    }
}
