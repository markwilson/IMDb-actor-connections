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
     * @return void
     *
     * @throws \RuntimeException As not yet implemented
     */
    public function disable($actorId, $movieId)
    {
        throw new \RuntimeException('Not yet implemented');
    }

    /**
     * Enable this role
     *
     * @param integer|string $actorId Actor DB ID
     * @param integer|string $movieId Movie DB ID
     *
     * @return void
     *
     * @throws \RuntimeException As not yet implemented
     */
    public function enable($actorId, $movieId)
    {
        throw new \RuntimeException('Not yet implemented');
    }
}
