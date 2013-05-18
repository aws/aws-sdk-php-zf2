<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'Aws' => 'Aws\Factory\AwsFactory'
        )
    ),

    'view_helpers' => array(
        'factories' => array(
            'Aws\View\Helper\S3Link' => 'Aws\Factory\S3LinkViewHelperFactory'
        ),

        'aliases' => array(
            's3link' => 'Aws\View\Helper\S3Link'
        )
    )
);
