<?php

ini_set('error_reporting', E_ALL);

$files = array(__DIR__ . '/../vendor/autoload.php', __DIR__ . '/../../../autoload.php');
foreach ($files as $file) {
    if (file_exists($file)) {
        $loader = require $file;
        break;
    }
}

if (!isset($loader)) {
    throw new RuntimeException('The vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

/* @var $loader \Composer\Autoload\ClassLoader */
$loader->add('Aws\\Tests\\', __DIR__);

unset($files, $file, $loader);
