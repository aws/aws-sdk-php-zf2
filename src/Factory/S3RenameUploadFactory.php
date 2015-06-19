<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use AwsModule\Filter\File\S3RenameUpload;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a S3RenameUpload file filter
 */
class S3RenameUploadFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var AwsSdk $awsSdk */
        $awsSdk = $parentLocator->get(AwsSdk::class);

        return new S3RenameUpload($awsSdk->createS3());
    }
}
