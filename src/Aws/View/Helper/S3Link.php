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

namespace Aws\View\Helper;

use Aws\S3\S3Client;
use Zend\View\Helper\AbstractHelper;

/**
 * View helper that can render a link to a S3 object. It can also create signed URLs
 */
class S3Link extends AbstractHelper
{
    /**
     * Amazon AWS endpoint
     */
    const S3_ENDPOINT = 's3.amazon.com';

    /**
     * @var S3Client
     */
    protected $client;

    /**
     * Constuctor
     *
     * @param S3Client $client
     */
    public function __construct(S3Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a link to a S3 object from a bucket. If expiration is not empty, then it is used to create
     * a signed URL
     *
     * @param  string $object The object name (full path)
     * @param  string $bucket The bucket name
     * @param  string $expiration The Unix timestamp to expire at or a string that can be evaluated by strtotime
     * @return string
     */
    public function __invoke($object, $bucket, $expiration = '')
    {
        $url = sprintf(
            'https://%s.%s/%s',
            trim($bucket, '/'),
            self::S3_ENDPOINT,
            ltrim($object, '/')
        );

        if (empty($expiration)) {
            return $url;
        }

        $request = $this->client->get($url);

        return $this->client->createPresignedUrl($request, $expiration);
    }
}
