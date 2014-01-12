<?php

namespace MarkWilson\Manager;

use Doctrine\DBAL\DBALException;

/**
 * Movie database manager
 *
 * @package MarkWilson\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class MovieManager extends AbstractManager
{
    /**
     * Table name
     */
    const TABLE_NAME = 'movies';

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

    /**
     * Disable movie
     *
     * @param integer $id Movie DB ID
     *
     * @return void
     */
    public function disable($id)
    {
        $this->getDbConnection()->update(self::TABLE_NAME, array('enabled' => 0), 'id = ' . (int)$id);
    }
}
