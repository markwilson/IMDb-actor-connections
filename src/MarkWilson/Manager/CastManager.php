<?php

namespace MarkWilson\Manager;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

class CastManager
{
    private $dbConnection;

    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function clear()
    {
        $this->dbConnection->exec('SET FOREIGN_KEY_CHECKS=0; TRUNCATE cast; SET FOREIGN_KEY_CHECKS=1;');
    }

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

    public function disable($actorId, $movieId)
    {
        $this->dbConnection->update('cast', array('enabled' => 0), 'actor_id = ' . (int)$actorId . ' AND movie_id = ' . (int)$movieId);
    }
}
