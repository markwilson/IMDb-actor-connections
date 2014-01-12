<?php

namespace MarkWilson\Manager;

use Doctrine\DBAL\DBALException;

/**
 * Cast DB manager
 *
 * @package MarkWilson\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class CastManager extends AbstractManager
{
    /**
     * Table name
     */
    const TABLE_NAME = 'cast';

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
            $this->getDbConnection()->insert(self::TABLE_NAME, array('actor_id' => $actorId, 'movie_id' => $movieId));
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
        $this->getDbConnection()->update('cast', array('enabled' => 0), 'actor_id = ' . (int)$actorId . ' AND movie_id = ' . (int)$movieId);
    }
}
