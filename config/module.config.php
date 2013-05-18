<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Aws' => 'Aws\Factory\AwsFactory'
        )
    ),

    'view_helpers' => array(
        'invokables' => array(
            'Aws\View\Helper\CloudFrontLink' => 'Aws\Factory\CloudFrontLinkViewHelperFactory',
        ),

        'factories' => array(
            'Aws\View\Helper\S3Link' => 'Aws\Factory\S3LinkViewHelperFactory'
        ),

        'aliases' => array(
            'cloudfrontlink' => 'Aws\View\Helper\CloudFrontLink',
            's3link'         => 'Aws\View\Helper\S3Link'
        )
    ),
);
