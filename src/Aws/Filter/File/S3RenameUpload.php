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

namespace Aws\Filter\File;

use Aws\S3\S3Client;
use Aws\Filter\Exception\MissingBucketException;
use Zend\Filter\File\RenameUpload;

/**
 * File filter that allow to directly upload to Amazon S3, and optionally rename the file
 */
class S3RenameUpload extends RenameUpload
{
    /**
     * @var S3Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $options = array(
        'bucket'               => null,
        'target'               => null,
        'use_upload_name'      => false,
        'use_upload_extension' => false,
        'overwrite'            => false,
        'randomize'            => false,
    );

    /**
     * @param S3Client $client
     * @param array    $options
     */
    public function __construct(S3Client $client, $options = array())
    {
        parent::__construct($options);

        // We need to register the S3 stream wrapper so that we can take advantage of the base class
        $this->client = $client;
        $this->client->registerStreamWrapper();
    }

    /**
     * Set the bucket name
     *
     * @param  string $bucket
     *
     * @return S3RenameUpload
     */
    public function setBucket($bucket)
    {
        $this->options['bucket'] = trim($bucket, '/');
        return $this;
    }

    /**
     * Get the bucket name
     *
     * @return string
     */
    public function getBucket()
    {
        return $this->options['bucket'];
    }

    /**
     * This method is overloaded so that the final target points to a URI using S3 protocol
     *
     * {@inheritdoc}
     */
    protected function getFinalTarget($uploadData)
    {
        // We cannot upload without a bucket
        if (null === $this->options['bucket']) {
            throw new MissingBucketException('No bucket was set when trying to upload a file to S3');
        }

        // Get the tmp file name and convert it to an S3 key
        $key = trim(str_replace('\\', '/', parent::getFinalTarget($uploadData)), '/');
        if (strpos($key, './') === 0) {
            $key = substr($key, 2);
        }

        return "s3://{$this->options['bucket']}/{$key}";
    }
}
