<?php

namespace MarkWilson\Command;

use Doctrine\DBAL\DBALException;
use MarkWilson\FileObject\ActorFileObject;
use MarkWilson\Manager\ActorManager;
use MarkWilson\Manager\CastManager;
use MarkWilson\Manager\MovieManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
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
             )
             ->addOption(
                 'dry-run',
                 null,
                 InputOption::VALUE_NONE,
                 'Dry run'
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
                $this->log($output, 'Starting truncate.');

                if (!$this->isDryRun($input)) {
                    $command = $this->getApplication()->find('imdb:truncate');

                    $input = new ArrayInput(array());
                    $command->run($input, $output);
                }

                $this->log($output, 'Truncate complete.');
            }

            $this->log($output, 'Starting import.');

            $actors = new ActorFileObject($fileName);

            // loop through actors
            while ($actors->valid()) {
                $actor = $actors->current();

                if ($actor->getTitles()->count() === 0) {
                    // no need to import actors with no titles
                    $this->log($output, '<comment>Skipped actor ' . $actor->getName() . '. No titles found.</comment>');
                } else {
                    $actors->next();

                    if (!$this->isDryRun($input)) {
                        // insert actor into database
                        $actorId = $this->actorManager->add($actor->getName());
                    } else {
                        $actorId = 12345;
                    }

                    // insert all titles into database (if not already there)
                    // insert link between actor and title
                    foreach ($actor->getTitles() as $title) {
                        if (!$this->isDryRun($input)) {
                            $movieId = $this->movieManager->add($title);
                        } else {
                            $movieId = 12345;
                        }

                        if (!$this->isDryRun($input)) {
                            $this->castManager->add($actorId, $movieId);
                        }
                    }

                    $this->log($output, 'Imported actor ' . $actor->getName() . '. ' . $actor->getTitles()->count() . ' titles.');
                }

                if ($actor->getName() === 'Perry, Matthew (I)') {
                    var_dump($actor);
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

    /**
     * Is it a dry run?
     *
     * @param InputInterface $input User input
     *
     * @return boolean
     */
    private function isDryRun(InputInterface $input)
    {
        return $input->getOption('dry-run');
    }

    /**
     * Is it quiet mode?
     *
     * @param InputInterface $input User input
     *
     * @return boolean
     */
    private function isQuietMode(InputInterface $input)
    {
        return $input->getOption('quiet');
    }

    /**
     * Write output log
     *
     * @param OutputInterface $output  User output
     * @param string          $message Log message
     *
     * @return void
     */
    private function log(OutputInterface $output, $message)
    {
        if (!$output->isQuiet()) {
            $output->writeln($message);
        }
    }
}
