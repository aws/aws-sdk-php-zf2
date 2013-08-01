<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Aws'                              => 'Aws\Factory\AwsFactory',
            'Aws\Session\SaveHandler\DynamoDb' => 'Aws\Factory\DynamoDbSessionSaveHandlerFactory'
        )
    ),

    'filters' => array(
        'factories' => array(
            'Aws\Filter\File\S3RenameUpload' => 'Aws\Factory\S3RenameUploadFactory'
        ),
        'aliases' => array(
            's3renameupload' => 'Aws\Filter\File\S3RenameUpload'
        )
    ),

    'view_helpers' => array(
        'factories' => array(
            'Aws\View\Helper\S3Link'         => 'Aws\Factory\S3LinkViewHelperFactory',
            'Aws\View\Helper\CloudFrontLink' => 'Aws\Factory\CloudFrontLinkViewHelperFactory'
        ),

        'aliases' => array(
            'cloudfrontlink' => 'Aws\View\Helper\CloudFrontLink',
            's3link'         => 'Aws\View\Helper\S3Link'
        )
    ),
);
