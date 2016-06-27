<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\Filter\File\S3RenameUpload;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a S3RenameUpload file filter
 */
class S3RenameUploadFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return S3RenameUpload
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var AwsSdk $awsSdk */
        $awsSdk = $container->get(AwsSdk::class);

        return new S3RenameUpload($awsSdk->createS3());
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        return $this($parentLocator, S3RenameUpload::class);
    }
}
