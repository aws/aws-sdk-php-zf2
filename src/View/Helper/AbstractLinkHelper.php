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
use Aws\View\Exception\InvalidSchemeException;

/**
 * Common functionality for link generating helpers.
 */
class AbstractLinkHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $scheme = 'https';

    /**
     * @var array
     */
    protected $supportedSchemes = array('http', 'https', null);

    /**
     * Set if HTTPS should be used for generating URLs
     *
     * @param bool $useSsl
     *
     * @return self
     * @deprecated
     */
    public function setUseSsl($useSsl)
    {
        $this->setScheme($useSsl ? 'https' : 'http');

        return $this;
    }

    /**
     * Get if HTTPS should be used for generating URLs
     *
     * @return bool
     * @deprecated
     */
    public function getUseSsl()
    {
        return $this->getScheme() === 'https';
    }

    /**
     * Set the scheme to use for generating URLs.  Supported schemes
     * are http, https and null (see {@link $supportedSchemes}).
     *
     * @param string $scheme
     * @throws InvalidSchemeException
     * @return self
     */
    public function setScheme($scheme)
    {
        if (!in_array($scheme, $this->supportedSchemes, true)) {
            $schemes = implode(', ', $this->supportedSchemes);

            throw new InvalidSchemeException('Schemes must be one of ' . $schemes);
        }

        $this->scheme = $scheme;

        return $this;
    }

    /**
     * Get the scheme to be used for generating URLs
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }
}
