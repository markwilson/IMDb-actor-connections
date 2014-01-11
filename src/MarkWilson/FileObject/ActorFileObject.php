<?php

namespace MarkWilson\FileObject;

use MarkWilson\Filter\MovieFilter;
use MarkWilson\Model\Actor;

/**
 * Extension of SplFileObject to get actor objects
 *
 * @package MarkWilson\FileObject
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class ActorFileObject extends \SplFileObject
{
    /**
     * Current actor data
     *
     * @var Actor
     */
    private $currentData;

    /**
     * Constructor.
     *
     * @param string $fileName Import file
     */
    public function __construct($fileName)
    {
        call_user_func_array(array('parent', '__construct'), func_get_args());

        // go line by line until we hit "THE ACTORS LIST"
        while (parent::valid()) {
            $line = parent::current();

            if (!preg_match('/^THE ACTORS LIST/', $line)) {
                parent::next();
            } else {
                break;
            }
        }

        // skip to /^----/
        while (parent::valid()) {
            $line = parent::current();

            if (!preg_match('/^----/', $line)) {
                parent::next();
            } else {
                break;
            }
        }

        // skip to next line
        if (parent::valid()) {
            parent::next();
        }

        // set up the initial current value
        $this->next();
    }

    /**
     * Get current actor
     *
     * @return Actor
     */
    public function current()
    {
        return $this->currentData;
    }

    /**
     * Find next actor
     *
     * @throws \RuntimeException If invalid format is detected
     */
    public function next()
    {
        parent::next();

        $actor = null;
        $titles = array();

        if (parent::valid()) {
            // current contains actor and title
            $line = parent::current();

            if (preg_match('/^----/', $line)) {
                $this->currentData = null;

                return;
            }

            if (!preg_match('/^([^\t]+)\t+(.*)$/', $line, $matches)) {
                throw new \RuntimeException('Line ' . (self::key() + 1) . ' does not match expected pattern.');
            }

            $actor = utf8_encode(trim($matches[1]));
            $titles[] = trim($matches[2]);

            parent::next();

            while (parent::valid() && trim(parent::current()) !== '') {
                $line = parent::current();

                if (trim($line) === '') {
                    break;
                }

                $titles[] = trim($line);

                parent::next();
            }
        }

        $filter = new MovieFilter();
        $titles = $filter->filter($titles);

        $titles = array_map(array($this, 'stripAdditionalData'), $titles);

        $this->currentData = new Actor($actor, $titles);
    }

    /**
     * Check if data is valid
     *
     * Checks if SplFileObject is valid and if we've got to the end of the actor data
     *
     * @return boolean
     */
    public function valid()
    {
        return (null !== $this->currentData);
    }

    /**
     * Rewind command - not available in this sub-class
     *
     * @throws \RuntimeException Always
     */
    public function rewind()
    {
        throw new \RuntimeException('It is not possible to rewind actor file objects.');
    }

    /**
     * Strip film title down to just the data we want to import
     *
     * @param string $title Film title
     *
     * @return string
     */
    private function stripAdditionalData($title)
    {
        // find first ( and remove everything after (and including) it
        $title = substr($title, 0, strpos($title, '(') - 1);

        return utf8_encode(preg_replace('/(^")|("$)/', '', $title));
    }
}
