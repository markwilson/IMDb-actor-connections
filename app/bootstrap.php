<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MarkWilson\Command\ImportCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Filesystem\Filesystem;

$connectionParams = require(__DIR__ . '/config/db.config.php');

$dbConfig   = new \Doctrine\DBAL\Configuration();
$connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $dbConfig);

$fileSystem    = new Filesystem();
$importCommand = new ImportCommand($fileSystem, $connection);

$application = new Application();
$application->add($importCommand);
$application->run();
