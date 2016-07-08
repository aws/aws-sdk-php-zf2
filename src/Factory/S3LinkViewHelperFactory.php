<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\View\Helper\S3Link;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a S3 link view helper
 */
class S3LinkViewHelperFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return S3Link
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var AwsSdk $awsSdk */
        $awsSdk = $container->get(AwsSdk::class);

        return new S3Link($awsSdk->createS3());
    }

    /**
     * {@inheritDoc}
     * @return S3Link
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        return $this($parentLocator, S3Link::class);
    }
}
