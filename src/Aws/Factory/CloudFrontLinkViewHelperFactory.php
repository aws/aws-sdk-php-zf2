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

namespace Aws\Factory;

use Aws\View\Helper\CloudFrontLink;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a CloudFront link view helper
 */
class CloudFrontLinkViewHelperFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return CloudFrontLink
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $parentLocator    = $serviceLocator->getServiceLocator();
        $cloudFrontClient = $parentLocator->get('Aws')->get('CloudFront');

        return new CloudFrontLink($cloudFrontClient);
    }
}
