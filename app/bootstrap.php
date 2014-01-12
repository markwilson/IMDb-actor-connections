<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MarkWilson\Command\ImportCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;
use MarkWilson\Manager\CastManager;
use Doctrine\DBAL;

$connectionParams = require(__DIR__ . '/config/db.config.php');

$dbConfig   = new DBAL\Configuration();
$connection = DBAL\DriverManager::getConnection($connectionParams, $dbConfig);

$castManager = new CastManager($connection);

$fileSystem    = new Filesystem();
$importCommand = new ImportCommand($fileSystem, $connection, $castManager);

$application = new Application();
$application->add($importCommand);
$application->run();
