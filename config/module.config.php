<?php

return [
    'service_manager' => [
        'factories' => [
            'Aws'                              => 'Aws\Factory\AwsFactory',
            'Aws\Session\SaveHandler\DynamoDb' => 'Aws\Factory\DynamoDbSessionSaveHandlerFactory'
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
