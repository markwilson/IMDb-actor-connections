<?php

namespace MarkWilson\Manager;

use Doctrine\DBAL\Connection;

class SingleKeyManager extends AbstractManager
{
    /**
     * Table configuration - must be overridden
     */
    const TABLE_KEY = '';

    /**
     * Constructor.
     *
     * @param Connection $dbConnection Database connection
     *
     * @throws \RuntimeException If table configuration is wrong
     */
    public function __construct(Connection $dbConnection)
    {
        parent::__construct($dbConnection);

        if (static::TABLE_KEY === '') {
            throw new \RuntimeException('Invalid table key');
        }
    }


    /**
     * Disable record
     *
     * @param integer|string $id Record DB ID
     *
     * @return void
     */
    public function disable($id)
    {
        $this->setEnabled($id, false);
    }

    /**
     * Enable record
     *
     * @param integer|string $id Record DB ID
     *
     * @return void
     */
    public function enable($id)
    {
        $this->setEnabled($id, true);
    }

    /**
     * Set enabled status of a row
     *
     * @param integer|string $key   Identifier
     * @param boolean        $value New value
     *
     * @return void
     */
    protected function setEnabled($key, $value)
    {
        if (is_int($key)) {
            $where = array('id' => (int)$key);
        } else {
            $where = array(static::TABLE_KEY => (string)$key);
        }

        $this->getDbConnection()->update(static::TABLE_NAME, array('enabled' => $value ? 1 : 0), $where);
    }
}
