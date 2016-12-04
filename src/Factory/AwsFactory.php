<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\Module;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate an AWS client
 */
class AwsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AwsSdk
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate the AWS SDK for PHP
        $config = $container->get('Config');
        $config = isset($config['aws']) ? $config['aws'] : [];

        $zend_framework_version = isset($config['aws_zf2']['zend_framework_version']) ? $config['aws_zf2']['zend_framework_version'] : '3.0';
        $config += [
            'ua_append' => [
                'ZF2/' . $zend_framework_version,
                'ZFMOD/' . Module::VERSION,
            ]
        ];

        return new AwsSdk($config);
    }

    /**
     * {@inheritDoc}
     * @return AwsSdk
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, AwsSdk::class);
    }
}
