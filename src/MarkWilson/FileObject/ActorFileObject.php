<?php

namespace MarkWilson\FileObject;

use MarkWilson\Model\Actor;

class ActorFileObject extends \SplFileObject
{
    private $currentData;

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

        $this->next();
    }

    /**
     * @return Actor
     */
    public function current()
    {
        return $this->currentData;
    }

    public function next()
    {
        parent::next();

        $actor = null;
        $titles = array();

        if (parent::valid()) {
            // current contains actor and title
            $line = parent::current();

            if (!preg_match('/^([^\t]+)\t+(.*)$/', $line, $matches)) {
                throw new \RuntimeException('Line ' . (self::key() + 1) . ' does not match expected pattern.');
            }

            $actor = trim($matches[1]);
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

        $titles = array_filter($titles, array($this, 'filterNonMovies'));
        $titles = array_map(array($this, 'stripAdditionalData'), $titles);

        $this->currentData = new Actor($actor, $titles);
    }

    public function valid()
    {
        $return = parent::valid();

        if ($return) {
            $return = !preg_match('/^----/', parent::current());
        }

        return $return;
    }

    public function rewind()
    {
        throw new \RuntimeException('It is not possible to rewind actor file objects.');
    }

    private function filterNonMovies($title)
    {
        // contains an episode reference
        if (preg_match('/^[^\(]+\([^\)]+\)\s+\{/', $title)) {
            return false;
        }

        if (preg_match('/^[\(]+\([^\)]+\)\s+\(TV\)/', $title)) {
            return false;
        }

        return true;
    }

    private function stripAdditionalData($title)
    {
        // find first ( and remove everything after (and including) it
        $title = substr($title, 0, strpos($title, '(') - 1);

        return preg_replace('/(^")|("$)/', '', $title);
    }
}
