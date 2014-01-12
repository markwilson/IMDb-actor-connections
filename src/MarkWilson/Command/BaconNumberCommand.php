<?php

namespace MarkWilson\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Calculate a Bacon number
 *
 * @package MarkWilson\Command
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class BaconNumberCommand extends Command
{
    private $dbConnection;

    /**
     * Constructor.
     *
     * @param Connection $dbConnection Database connection
     */
    public function __construct(Connection $dbConnection)
    {
        parent::__construct();

        $this->dbConnection = $dbConnection;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('imdb:baconnumber')
            ->setAliases(array('imdb:bacon-number'))
            ->setDescription('Find someone\'s bacon number')
            ->addArgument('actor', InputArgument::REQUIRED, 'Actor to search for');
    }

    /**
     * Execute the import command
     *
     * @param InputInterface  $input  User input
     * @param OutputInterface $output User output
     *
     * @return void
     *
     * @throws \RuntimeException As not yet implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO run a search for someone's bacon number
        throw new \RuntimeException('Not yet implemented');
    }
}
