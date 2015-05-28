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

namespace Aws\Tests;

use Aws\Module as AwsModule;
use Zend\ServiceManager\Config as ServiceConfig;
use Zend\ServiceManager\ServiceManager;

abstract class BaseModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instantiate the Service Manager with the configuration from the AWS module
     *
     * @return ServiceManager
     */
    protected function createServiceManagerForTest()
    {
        $module = new AwsModule();
        $config = $module->getConfig();

        $serviceConfig  = new ServiceConfig($config['service_manager']);
        $serviceManager = new ServiceManager($serviceConfig);

        return $serviceManager;
    }
}
