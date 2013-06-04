<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Aws' => 'Aws\Factory\AwsFactory'
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
