<?php

namespace MarkWilson\Manager;

/**
 * Actor database manager
 *
 * @package MarkWilson\Manager
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class ActorManager extends SingleKeyManager
{
    /**
     * Table name
     */
    const TABLE_NAME = 'actors';
    const TABLE_KEY  = 'name';

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
}
