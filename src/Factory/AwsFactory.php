<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\Module;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Version\Version;

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
        $config += [
            'ua_append' => [
                'ZF2/' . Version::VERSION,
                'ZFMOD/' . Module::VERSION,
            ]
        ];

        return new AwsSdk($config);
    }
}
