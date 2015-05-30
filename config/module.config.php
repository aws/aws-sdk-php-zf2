<?php

use AwsModule\Factory\AwsFactory;
use AwsModule\Factory\CloudFrontLinkViewHelperFactory;
use AwsModule\Factory\DynamoDbSessionSaveHandlerFactory;
use AwsModule\Factory\S3LinkViewHelperFactory;
use AwsModule\Factory\S3RenameUploadFactory;
use AwsModule\Filter\File\S3RenameUpload;
use Aws\Sdk as Aws;
use AwsModule\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use AwsModule\View\Helper\CloudFrontLink;
use AwsModule\View\Helper\S3Link;

return [
    'service_manager' => [
        'factories' => [
            Aws::class                 => AwsFactory::class,
            DynamoDbSaveHandler::class => DynamoDbSessionSaveHandlerFactory::class
        ]
    ],

    'filters' => [
        'factories' => [
            S3RenameUpload::class => S3RenameUploadFactory::class
        ],
        'aliases' => [
            's3renameupload' => S3RenameUpload::class
        ]
    ],

    'view_helpers' => [
        'factories' => [
            S3Link::class         => S3LinkViewHelperFactory::class,
            CloudFrontLink::class => CloudFrontLinkViewHelperFactory::class
        ],

        'aliases' => [
            'cloudfrontlink' => CloudFrontLink::class,
            's3link'         => S3Link::class
        ]
    ],
];
