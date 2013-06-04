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

use Aws\CloudFront\CloudFrontClient;
use Aws\View\Exception\InvalidDomainNameException;
use Zend\View\Helper\AbstractHelper;

/**
 * View helper that can render a link to a CloudFront object. It can also create signed URLs
 * using a canned policy
 */
class CloudFrontLink extends AbstractHelper
{
    /**
     * Amazon AWS endpoint
     */
    const CLOUD_FRONT_ENDPOINT = 'cloudfront.net';

    /**
     * @var CloudFrontClient
     */
    protected $client;

    /**
     * @var bool
     */
    protected $useSsl = true;

    /**
     * @var string
     */
    protected $defaultDomain = '';

    /**
     * Constructor
     *
     * @param CloudFrontClient $client
     */
    public function __construct(CloudFrontClient $client)
    {
        $this->client = $client;
    }

    /**
     * Set if HTTPS should be used for generating URLs
     *
     * @param bool $useSsl
     */
    public function setUseSsl($useSsl)
    {
        $this->useSsl = (bool) $useSsl;
    }

    /**
     * Get if HTTPS should be used for generating URLs
     *
     * @return bool
     */
    public function getUseSsl()
    {
        return $this->useSsl;
    }

    /**
     * Set the CloudFront domain to use if none is provided
     *
     * @param string $defaultDomain
     */
    public function setDefaultDomain($defaultDomain)
    {
        $this->defaultDomain = (string) $defaultDomain;
    }

    /**
     * Get the CloudFront domain to use if none is provided
     *
     * @return string
     */
    public function getDefaultDomain()
    {
        return $this->defaultDomain;
    }

    /**
     * Create a link to a CloudFront object
     *
     * @param  string     $object
     * @param  string     $domain
     * @param  string|int $expiration
     * @throws InvalidDomainNameException
     * @return string
     */
    public function __invoke($object, $domain = '', $expiration = '')
    {
        if (empty($domain)) {
            $domain = $this->getDefaultDomain();
        }

        // If $domain is still empty, we throw an exception as it makes no sense
        if (empty($domain)) {
            throw new InvalidDomainNameException('An empty Cloud Front domain name was given');
        }

        $url = sprintf(
            '%s://%s.%s/%s',
            $this->useSsl ? 'https' : 'http',
            str_replace('.cloudfront.net', '', rtrim($domain, '/')), // Remove .cloudfront.net if provided as we include it already
            self::CLOUD_FRONT_ENDPOINT,
            ltrim($object, '/')
        );

        if (empty($expiration)) {
            return $url;
        }

        return $this->client->getSignedUrl(array(
            'url'     => $url,
            'expires' => $expiration
        ));
    }
}
