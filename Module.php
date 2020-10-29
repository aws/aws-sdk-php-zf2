<?php

namespace AwsModule;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Zend Framework 2 module that allows easy consumption of the AWS SDK for PHP
 */
class Module implements ConfigProviderInterface
{
    const VERSION = '4.3.0';

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
