<?php

namespace MarkWilson\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use MarkWilson\FileObject\ActorFileObject;
use MarkWilson\Manager\ActorManager;
use MarkWilson\Manager\CastManager;
use MarkWilson\Manager\MovieManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Importer of IMDb data
 *
 * @package MarkWilson\Command
 * @author  Mark Wilson <mark@89allport.co.uk>
 */
class ImportCommand extends Command
{
    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    private $fileSystem;

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
     * @param Filesystem   $fileSystem   Filesystem instance
     * @param CastManager  $castManager  Cast manager
     * @param ActorManager $actorManager Actor manager
     * @param MovieManager $movieManager Movie manager
     */
    public function __construct(Filesystem $fileSystem, CastManager $castManager, ActorManager $actorManager, MovieManager $movieManager)
    {
        parent::__construct();

        $this->fileSystem   = $fileSystem;
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
        $this->setName('imdb:import')
             ->setDescription('Import IMDb actors/actresses')
             ->addArgument(
                 'filename',
                 InputArgument::REQUIRED,
                 'Which file should be imported?'
             )
             ->addOption(
                 'truncate',
                 null,
                 InputOption::VALUE_NONE,
                 'If set, truncates all data'
             );
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
        $fileName = $input->getArgument('filename');

        try {
            // validate $fileName exists and is the write format (*.list, text/plain)
            if (!$this->fileSystem->exists($fileName)) {
                throw new \RuntimeException('File does not exist.');
            }

            $fileInfo = new \SplFileInfo($fileName);
            if (!$fileInfo->isFile() || !$fileInfo->isReadable()) {
                throw new \RuntimeException('File is not readable.');
            }

            if ($input->getOption('truncate')) {
                $output->writeln('Starting truncate.');

                // clear the current database
                $this->castManager->clear();
                $this->actorManager->clear();
                $this->movieManager->clear();

                $output->writeln('Truncate complete.');
            }

            $output->writeln('Starting import.');

            $actors = new ActorFileObject($fileName);

            // loop through actors
            while ($actors->valid()) {
                $actor = $actors->current();

                if ($actor->getTitles()->count() === 0) {
                    // no need to import actors with no titles
                    $output->writeln('<comment>Skipped actor ' . $actor->getName() . '. No titles found.</comment>');
                } else {
                    $actors->next();

                    // insert actor into database
                    $actorId = $this->actorManager->add($actor->getName());

                    // insert all titles into database (if not already there)
                    // insert link between actor and title
                    foreach ($actor->getTitles() as $title) {
                        $movieId = $this->movieManager->add($title);

                        $this->castManager->add($actorId, $movieId);
                    }

                    $output->writeln('Imported actor ' . $actor->getName() . '. ' . $actor->getTitles()->count() . ' titles.');
                }

                $actors->next();
            }
        } catch (\RuntimeException $e) {
            // handle error output
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');

            return;
        }

        $output->writeln('<info>Import complete.</info>');
    }
}
