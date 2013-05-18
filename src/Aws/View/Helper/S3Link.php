<?php
/**
 * Copyright 2013 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License").
 * You may not use this file except in compliance with the License.
 * A copy of the License is located at
 *
 * http://aws.amazon.com/apache2.0
 *
 * or in the "license" file accompanying this file. This file is distributed
 * on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

use Zend\View\Helper\AbstractHelper;

/**
 * View helper that can render a link to a S3 object, or turn the link to a CloudFront domain
 */
class S3Link extends AbstractHelper
{
    /**
     * @var bool
     */
    protected $useCloudFront = false;

    /**
     * @var null|string
     */
    protected $cloudFrontDomain;

    /**
     * @param bool $useCloudFront
     * @param string $cloudFrontDomain
     */
    public function __construct($useCloudFront = false, $cloudFrontDomain = '')
    {
        $this->useCloudFront = $useCloudFront;

        if (!empty($cloudFrontDomain)) {
            $this->cloudFrontDomain = $cloudFrontDomain;
        }
    }

    /**
     * @param  string $bucket
     * @param  string $object
     * @return string
     */
    public function __invoke($bucket, $object)
    {
        if ($this->useCloudFront) {
            return $this->generateCloudFrontLink($object);
        }

        return $this->generateS3Link($bucket, $object);
    }

    /**
     * @param  string $object
     * @return string
     */
    public function generateCloudFrontLink($object)
    {
        return sprintf(
            'https://%s.cloudfront.net/%s',
            $this->cloudFrontDomain,
            $object
        );
    }

    /**
     * @param  string $bucket
     * @param  string $object
     * @return string
     */
    public function generateS3Link($bucket, $object)
    {
        return sprintf(
            'https://%s.s3.amazonaws.com/%s',
            $bucket, $object
        );
    }
}
