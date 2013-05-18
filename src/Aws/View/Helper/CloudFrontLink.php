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

use Zend\View\Helper\AbstractHelper;

/**
 * View helper that can render a link to a CloudFront object
 */
class CloudFrontLink extends AbstractHelper
{
    /**
     * Amazon AWS endpoint
     */
    const CLOUD_FRONT_ENDPOINT = 'cloudfront.net';

    /**
     * Default CloudFront domain to use
     *
     * @var string
     */
    protected $defaultDomain = '';

    /**
     * Constructor
     *
     * @param string $defaultDomain
     */
    public function __construct($defaultDomain = '')
    {
        $this->defaultDomain = $defaultDomain;
    }

    /**
     * Set the default CloudFront domain (which is used if none is specified when creating a link)
     *
     * @param string $defaultDomain
     */
    public function setDefaultDomain($defaultDomain)
    {
        $this->defaultDomain = $defaultDomain;
    }

    /**
     * Get the default CloudFront domain (which is used if none is specified when creating a link)
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
     * @param  string $object
     * @param  string $domain
     * @return string
     */
    public function __invoke($object, $domain = '')
    {
        if (empty($domain)) {
            $domain = $this->getDefaultDomain();
        }

        // @TODO: should we throw an exception if $domain is still empty?

        $url = sprintf(
            'https://%s.%s/%s',
            ltrim($domain, '.cloudfront.net'), // Trim the end part because we already include it
            self::CLOUD_FRONT_ENDPOINT,
            ltrim($object, '/')
        );

        return $url;
    }
}
