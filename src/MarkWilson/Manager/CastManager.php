<?php

namespace MarkWilson\Manager;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * Cast DB manager
 *
 * @package MarkWilson\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class CastManager
{
    /**
     * Database connection
     *
     * @var Connection
     */
    private $dbConnection;

    /**
     * Constructor.
     *
     * @param Connection $dbConnection Database connection
     */
    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    /**
     * Clear the cast table
     *
     * @return void
     */
    public function clear()
    {
        $this->dbConnection->exec('SET FOREIGN_KEY_CHECKS=0; TRUNCATE cast; SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Add a new link between actor and movie
     *
     * @param integer $actorId     Actor DB ID
     * @param integer $movieId     Movie DB ID
     * @param boolean $failOnError Fail if an error occurs
     *
     * @return void
     *
     * @throws DBALException If we couldn't add this role
     */
    public function add($actorId, $movieId, $failOnError = false)
    {
        try {
            $this->dbConnection->insert('cast', array('actor_id' => $actorId, 'movie_id' => $movieId));
        } catch (DBALException $e) {
            if ($failOnError) {
                throw $e;
            }
        }
    }

    /**
     * Disable this role
     *
     * @param integer $actorId Actor DB ID
     * @param integer $movieId Movie DB ID
     *
     * @eeturn void
     */
    public function disable($actorId, $movieId)
    {
        $this->dbConnection->update('cast', array('enabled' => 0), 'actor_id = ' . (int)$actorId . ' AND movie_id = ' . (int)$movieId);
    }
}
