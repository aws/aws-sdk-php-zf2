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

namespace AwsTests\Factory;

use Aws\Factory\AwsFactory;
use Aws\Sdk as AwsSdk;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AWS Module test cases
 */
class AwsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanFetchAwsFromServiceManager()
    {
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with('Config')->willReturn([]);

        $awsFactory = new AwsFactory();

        /** @var $aws AwsSdk */
        $aws = $awsFactory->createService($serviceLocator);

        $this->assertInstanceOf(AwsSdk::class, $aws);
    }
}
