<?php

use Aws\Factory\AwsFactory;
use Aws\Factory\CloudFrontLinkViewHelperFactory;
use Aws\Factory\DynamoDbSessionSaveHandlerFactory;
use Aws\Factory\S3LinkViewHelperFactory;
use Aws\Factory\S3RenameUploadFactory;
use Aws\Filter\File\S3RenameUpload;
use Aws\Sdk as Aws;
use Aws\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Aws\View\Helper\CloudFrontLink;
use Aws\View\Helper\S3Link;

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
