<?php

namespace AwsModule;

use Aws\Sdk as Aws;

final class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
            'filters' => $this->getFiltersConfig(),
            'view_helpers' => $this->getViewHelpersConfig(),
        ];
    }

    public function getDependencyConfig()
    {
        return [
            'factories' => [
                Aws::class => Factory\AwsFactory::class,
                Session\SaveHandler\DynamoDb::class => Factory\DynamoDbSessionSaveHandlerFactory::class
            ]
        ];
    }

    public function getFiltersConfig()
    {
        return [
            'aliases' => [
                's3renameupload' => Filter\File\S3RenameUpload::class
            ]
            'factories' => [
                Filter\File\S3RenameUpload::class => Factory\S3RenameUploadFactory::class
            ],
        ];
    }

    public function getViewHelpersConfig()
    {
        return [
            'aliases' => [
                'cloudfrontlink' => View\Helper\CloudFrontLink::class,
                's3link'         => View\Helper\S3Link::class
            ]
            'factories' => [
                View\Helper\S3Link::class         => Factory\S3LinkViewHelperFactory::class,
                View\Helper\CloudFrontLink::class => Factory\CloudFrontLinkViewHelperFactory::class
            ],
        ];
    }
}
