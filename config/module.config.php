<?php

use Aws\Factory\AwsFactory;
use Aws\Factory\DynamoDbSessionSaveHandlerFactory;
use Aws\Sdk as Aws;
use Aws\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;

return [
    'service_manager' => [
        'factories' => [
            Aws::class                 => AwsFactory::class,
            DynamoDbSaveHandler::class => DynamoDbSessionSaveHandlerFactory::class
        ]
    ],

    'filters' => [
        'factories' => [
            'Aws\Filter\File\S3RenameUpload' => 'Aws\Factory\S3RenameUploadFactory'
        ],
        'aliases' => [
            's3renameupload' => 'Aws\Filter\File\S3RenameUpload'
        ]
    ],

    'view_helpers' => [
        'factories' => [
            'Aws\View\Helper\S3Link'         => 'Aws\Factory\S3LinkViewHelperFactory',
            'Aws\View\Helper\CloudFrontLink' => 'Aws\Factory\CloudFrontLinkViewHelperFactory'
        ],

        'aliases' => [
            'cloudfrontlink' => 'Aws\View\Helper\CloudFrontLink',
            's3link'         => 'Aws\View\Helper\S3Link'
        ]
    ],
];
