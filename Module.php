<?php

namespace Aws; // Desired namespace: Aws\Bridge\Zf2

use Aws\Common\Aws;
use Zend\Config\Config;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Zend Framework 2 module that allows easy consumption of the AWS SDK for PHP
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
//            'Zend\Loader\ClassMapAutoloader' => array(
//                __DIR__ . '/autoload_classmap.php', // @TODO not sure if I need this or not
//            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

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
                    /** @var $config Config */
                    $config = $serviceManager->get('Config');
                    return Aws::factory($config->get('aws'));
                },
            )
        );
    }
}
