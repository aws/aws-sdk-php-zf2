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

namespace AwsTests;

use Aws\Common\Client\UserAgentListener;
use Aws\Factory\AwsFactory;
use Aws\S3\S3Client;
use Aws\Tests\BaseModuleTest;
use Guzzle\Common\Event;
use Guzzle\Service\Client;

/**
 * AWS Module test cases
 */
class ModuleTest extends BaseModuleTest
{
    public function testCanFetchAwsFromServiceManager()
    {
        $awsFactory     = new AwsFactory();
        $serviceManager = $this->createServiceManagerForTest();

        $serviceManager->setService('config', array());

        /** @var $aws \Guzzle\Service\Builder\ServiceBuilder */
        $aws = $awsFactory->createService($serviceManager);

        $this->assertInstanceOf('Guzzle\Service\Builder\ServiceBuilderInterface', $aws);
        $this->assertTrue($aws->getEventDispatcher()->hasListeners('service_builder.create_client'));
    }

    public function testCanAddZf2ToUserAgent()
    {
        $factory = new AwsFactory();
        $client  = S3Client::factory();
        $event   = new Event(array('client' => $client));

        $factory->onCreateClient($event);
        $clientParams = $client->getConfig()->get(Client::COMMAND_PARAMS);

        $this->assertArrayHasKey(UserAgentListener::OPTION, $clientParams);
        $this->assertStringStartsWith('ZF2', $clientParams[UserAgentListener::OPTION]);
    }
}
