<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\View\Helper\CloudFrontLink;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a CloudFront link view helper
 */
class CloudFrontLinkViewHelperFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return CloudFrontLink
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var AwsSdk $awsSdk */
        $awsSdk = $container->get(AwsSdk::class);

        return new CloudFrontLink($awsSdk->createCloudFront());
    }

    /**
     * {@inheritDoc}
     * @return CloudFrontLink
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        return $this($parentLocator, CloudFrontLink::class);
    }
}
