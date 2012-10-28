<?php

namespace Aws;

use Aws\Common\Aws;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Zend Framework 2 module that allows easy consumption of the AWS SDK for PHP
 */
class Module implements ConfigProviderInterface, ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return array(
            'aws' => array()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'aws' => function (ServiceManager $serviceManager) {
                    $config = $serviceManager->get('Config');
                    return Aws::factory($config['aws']);
                },
            )
        );
    }
}
