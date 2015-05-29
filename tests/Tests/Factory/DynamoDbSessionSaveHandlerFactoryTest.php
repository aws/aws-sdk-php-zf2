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

namespace Aws\Tests\Factory;

use Aws\Factory\DynamoDbSessionSaveHandlerFactory;
use Aws\Sdk as AwsSdk;
use Aws\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * DynamoDB-backed session save handler tests
 */
class DynamoDbSessionSaveHandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanFetchSaveHandlerFromServiceManager()
    {
        $config = [
            'aws' => [
                'region'  => 'us-east-1',
                'version' => 'latest'
            ],
            'aws_zf2' => [
                'session' => [
                    'save_handler' => [
                        'dynamodb' => []
                    ]
                ]
            ]
        ];

        $awsSdk = new AwsSdk($config['aws']);

        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->at(0))->method('get')->with('Config')->willReturn($config);
        $serviceLocator->expects($this->at(1))->method('get')->with(AwsSdk::class)->willReturn($awsSdk);


        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();

        /** @var $saveHandler \Aws\Session\SaveHandler\DynamoDb */
        $saveHandler = $saveHandlerFactory->createService($serviceLocator);

        $this->assertInstanceOf(DynamoDbSaveHandler::class, $saveHandler);
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testExceptionThrownWhenSaveHandlerConfigurationDoesNotExist()
    {
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with('Config')->willReturn([]);

        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();

        $saveHandlerFactory->createService($serviceLocator);
    }
}
