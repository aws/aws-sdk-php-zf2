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

namespace AwsModule\View\Helper;

use Aws\S3\S3Client;
use AwsModule\View\Exception\InvalidDomainNameException;
use Zend\View\Helper\AbstractHelper;

/**
 * View helper that can render a link to a S3 object. It can also create signed URLs
 */
class S3Link extends AbstractHelper
{
    /**
     * @var S3Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $defaultBucket = '';

    /**
     * @param S3Client $client
     */
    public function __construct(S3Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set the default bucket to use if none is provided
     *
     * @param string $defaultBucket
     *
     * @return self
     */
    public function setDefaultBucket($defaultBucket)
    {
        $this->defaultBucket = (string) $defaultBucket;

        return $this;
    }

    /**
     * Get the default bucket to use if none is provided
     *
     * @return string
     */
    public function getDefaultBucket()
    {
        return $this->defaultBucket;
    }

    /**
     * Create a link to a S3 object from a bucket. If expiration is not empty, then it is used to create
     * a signed URL
     *
     * @param  string     $object The object name (full path)
     * @param  string     $bucket The bucket name
     * @param  string|int $expiration The Unix timestamp to expire at or a string that can be evaluated by strtotime
     * @throws InvalidDomainNameException
     * @return string
     */
    public function __invoke($object, $bucket = '', $expiration = '')
    {
        $bucket = trim($bucket ?: $this->getDefaultBucket(), '/');

        if (empty($bucket)) {
            throw new InvalidDomainNameException('An empty bucket name was given');
        }

        if ($expiration) {
            $command = $this->client->getCommand('GetObject', [
                'Bucket' => $bucket,
                'Key'    => $object
            ]);

            return $this->client->createPresignedRequest($command, $expiration)->getUri()->__toString();
        } else {
            return $this->client->getObjectUrl($bucket, $object);
        }
    }
}
