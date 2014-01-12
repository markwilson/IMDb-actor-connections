<?php

namespace MarkWilson\Manager;

use MarkWilson\Manager\AbstractManager;

/**
 * Actor database manager
 *
 * @package MarkWilson\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class ActorManager extends AbstractManager
{
    /**
     * Table name
     */
    const TABLE_NAME = 'actors';

    /**
     * Clear actors table
     *
     * @return void
     */
    public function clear()
    {
        parent::clear(self::TABLE_NAME);
    }

    /**
     * Add a new actor
     *
     * @param string $name Actor name
     *
     * @return string
     */
    public function add($name)
    {
        $this->getDbConnection()->insert(self::TABLE_NAME, array('name' => $name));

        return $this->getDbConnection()->lastInsertId();
    }

    /**
     * Disable an actor
     *
     * @param integer $id Actor DB ID
     *
     * @return void
     */
    public function disable($id)
    {
        $this->getDbConnection()->update(self::TABLE_NAME, array('enabled' => 0), 'id = ' . (int)$id);
    }
}
