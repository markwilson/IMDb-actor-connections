<?php

namespace MarkWilson\Command;

use MarkWilson\Manager\ActorManager;
use MarkWilson\Manager\CastManager;
use MarkWilson\Manager\MovieManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Truncate database
 *
 * @package MarkWilson\Command
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class TruncateCommand extends Command
{
    /**
     * Cast manager
     *
     * @var CastManager
     */
    private $castManager;

    /**
     * Actor manager
     *
     * @var ActorManager
     */
    private $actorManager;

    /**
     * Movie manager
     *
     * @var MovieManager
     */
    private $movieManager;

    /**
     * Constructor.
     *
     * @param CastManager  $castManager  Cast manager
     * @param ActorManager $actorManager Actor manager
     * @param MovieManager $movieManager Movie manager
     */
    public function __construct(CastManager $castManager, ActorManager $actorManager, MovieManager $movieManager)
    {
        parent::__construct();

        $this->castManager  = $castManager;
        $this->actorManager = $actorManager;
        $this->movieManager = $movieManager;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('imdb:truncate')->setDescription('Truncate database');
    }

    /**
     * Execute the import command
     *
     * @param InputInterface  $input  User input
     * @param OutputInterface $output User output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // clear the current database
        $this->castManager->clear();
        $this->actorManager->clear();
        $this->movieManager->clear();

        $output->writeln('<info>Truncate complete.</info>');
    }
}
