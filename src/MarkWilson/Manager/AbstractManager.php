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
     * Table configuration - must be overridden
     */
    const TABLE_NAME = '';

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
     *
     * @throws \RuntimeException If table name is not set
     */
    public function __construct(Connection $dbConnection)
    {
        $this->dbConnection = $dbConnection;

        if (static::TABLE_NAME === '') {
            throw new \RuntimeException('Invalid table name');
        }
    }

    /**
     * Clear a table
     *
     * @return void
     */
    public function clear()
    {
        $this->getDbConnection()->exec('SET FOREIGN_KEY_CHECKS=0; TRUNCATE ' . (string)static::TABLE_NAME . '; SET FOREIGN_KEY_CHECKS=1;');
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
}
