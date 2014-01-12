<?php

namespace MarkWilson\Manager;

use Doctrine\DBAL\DBALException;

/**
 * Movie database manager
 *
 * @package MarkWilson\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class MovieManager extends SingleKeyManager
{
    /**
     * Table configuration
     */
    const TABLE_NAME = 'movies';
    const TABLE_KEY  = 'title';

    /**
     * Add a new movie
     *
     * @param string $title Movie title
     *
     * @return integer
     */
    public function add($title)
    {
        try {
            $this->getDbConnection()->insert('movies', array('title' => $title));
            $movieId = $this->getDbConnection()->lastInsertId();
        } catch (DBALException $e) {
            // possible this was because the key already exists so try and load in the movie
            $movieId = $this->getDbConnection()->fetchColumn('SELECT id FROM movies WHERE title = ?', array($title));
        }

        return $movieId;
    }
}
