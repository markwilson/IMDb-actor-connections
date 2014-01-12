<?php

namespace MarkWilson\Manager;

use Doctrine\DBAL\Connection;

/**
 * Abstract database manager
 *
 * @package MarkWilson\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
abstract class AbstractManager
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
     * Get database connection
     *
     * @return Connection
     */
    protected function getDbConnection()
    {
        return $this->dbConnection;
    }

    /**
     * Clear a table
     *
     * @param string $tableName Table name
     */
    protected function clear($tableName)
    {
        $this->getDbConnection()->exec('SET FOREIGN_KEY_CHECKS=0; TRUNCATE ' . (string)$tableName . '; SET FOREIGN_KEY_CHECKS=1;');
    }
}
