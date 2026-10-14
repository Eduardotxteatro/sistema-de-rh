#!/usr/bin/php -q
<?php
// Check platform requirements
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
require dirname(__DIR__) . '/config/requirements.php';
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use Cake\Console\CommandRunner;

// Build the runner with an application and root executable name.
$runner = new CommandRunner(new Application(dirname(__DIR__) . '/config'), 'cake');
exit($runner->run($argv));
