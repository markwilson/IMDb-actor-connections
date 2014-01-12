<?php

namespace MarkWilson\Command;

use MarkWilson\Manager\ActorManager;
use MarkWilson\Manager\MovieManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Disable a database row
 *
 * @package MarkWilson\Command
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class DisableCommand extends Command
{
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
     * @param ActorManager $actorManager Actor manager
     * @param MovieManager $movieManager Movie manager
     */
    public function __construct(ActorManager $actorManager, MovieManager $movieManager)
    {
        parent::__construct();

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
        $this->setName('imdb:disable')
             ->setDescription('Disable a record')
             ->addOption('type', null, InputOption::VALUE_REQUIRED, 'Type of record to disable')
             ->addArgument('key', InputArgument::REQUIRED, 'Key of record to disable');
    }

    /**
     * Execute the import command
     *
     * @param InputInterface  $input  User input
     * @param OutputInterface $output User output
     *
     * @return void
     *
     * @throws \RuntimeException If invalid input data is provided
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        switch ($input->getOption('type')) {
            case 'actor':
                $this->actorManager->disable($input->getArgument('key'));
                break;
            case 'cast':
                throw new \RuntimeException('Cast disable is not yet implemented.');
            case 'movie':
                $this->movieManager->disable($input->getArgument('key'));
                break;
            default:
                throw new \RuntimeException('Type must be one of actor, or movie.');
        }

        $output->writeln('<info>Complete.</info>');
    }
}
