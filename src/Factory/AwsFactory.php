<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate an AWS client
 */
class AwsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return AwsSdk
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Instantiate the AWS SDK for PHP
        $config = $serviceLocator->get('Config');
        $config = isset($config['aws']) ? $config['aws'] : [];

        return new AwsSdk($config);
    }
}
