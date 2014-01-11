<?php

namespace MarkWilson\Command;

use MarkWilson\FileObject\ActorFileObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
     * Constructor.
     *
     * @param Filesystem $fileSystem Filesystem instance
     */
    public function __construct(Filesystem $fileSystem)
    {
        parent::__construct();

        $this->fileSystem = $fileSystem;
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

            $output->writeln('Starting import.');

            $actors = new ActorFileObject($fileName);

            // loop through actors
            while ($actors->valid()) {
                $actor = $actors->current();

                if ($actor->getTitles()->count() === 0) {
                    // no need to import actors with no titles
                    $output->writeln('<comment>Skipped actor ' . $actor->getName() . '. No titles found.</comment>');
                } else {
                    // insert actor into database
                    // insert all titles into database (if not already there)

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
