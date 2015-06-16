<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\View\Helper\CloudFrontLink;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a CloudFront link view helper
 */
class CloudFrontLinkViewHelperFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return CloudFrontLink
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var AwsSdk $awsSdk */
        $awsSdk = $parentLocator->get(AwsSdk::class);

        return new CloudFrontLink($awsSdk->createCloudFront());
    }
}
