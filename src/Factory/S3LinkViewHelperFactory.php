<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\View\Helper\S3Link;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a S3 link view helper
 */
class S3LinkViewHelperFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return S3Link
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var AwsSdk $awsSdk */
        $awsSdk = $parentLocator->get(AwsSdk::class);

        return new S3Link($awsSdk->createS3());
    }
}
