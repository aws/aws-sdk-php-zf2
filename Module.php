<?php

namespace AwsModule;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Zend Framework 2 module that allows easy consumption of the AWS SDK for PHP
 */
class Module implements ConfigProviderInterface
{
    const VERSION = '4.0.0';

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $provider = new ConfigProvider();

        return [
            'service_manager' => $provider->getDependencyConfig(),
            'filters' => $provider->getFiltersConfig(),
            'view_helpers' => $provider->getViewHelpersConfig(),
        ];
    }
}
