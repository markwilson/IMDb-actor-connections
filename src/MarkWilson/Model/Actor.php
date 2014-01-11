<?php

namespace MarkWilson\Model;

class Actor
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
     */
    public function __construct($name, array $titles)
    {
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
