<?php

namespace MarkWilson\Model;

/**
 * Actor value object
 *
 * @package MarkWilson\Model
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
final class Actor
{
    /**
     * Actor's name
     *
     * @var string
     */
    private $name;

    /**
     * Credited titles
     *
     * @var array
     */
    private $titles;

    /**
     * Constructor.
     *
     * @param string $name   Actor's name
     * @param array  $titles Credited titles
     *
     * @throws \Exception If no name is provided
     */
    public function __construct($name, array $titles)
    {
        if ($name === '') {
            throw new \Exception('No name provided');
        }

        $this->name   = $name;
        $this->titles = $titles;
    }

    /**
     * Get actor's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get actor's titles
     *
     * @return \ArrayIterator
     */
    public function getTitles()
    {
        return new \ArrayIterator($this->titles);
    }
}
