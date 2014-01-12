<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MarkWilson\Command\ImportCommand;
use MarkWilson\Command\TruncateCommand;
use MarkWilson\Command\DisableCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use MarkWilson\Manager\CastManager;
use MarkWilson\Manager\ActorManager;
use MarkWilson\Manager\MovieManager;
use Doctrine\DBAL;

$connectionParams = require(__DIR__ . '/config/db.config.php');

$dbConfig   = new DBAL\Configuration();
$connection = DBAL\DriverManager::getConnection($connectionParams, $dbConfig);

$castManager  = new CastManager($connection);
$actorManager = new ActorManager($connection);
$movieManager = new MovieManager($connection);

$fileSystem    = new Filesystem();
$importCommand = new ImportCommand($fileSystem, $castManager, $actorManager, $movieManager);

$truncateCommand = new TruncateCommand($castManager, $actorManager, $movieManager);
$disableCommand  = new DisableCommand($actorManager, $movieManager);

$application = new Application();
$application->add($importCommand);
$application->add($truncateCommand);
$application->add($disableCommand);
$application->run();
