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

use Aws\Factory\DynamoDbSessionSaveHandlerFactory;
use Aws\Tests\BaseModuleTest;

/**
 * DynamoDB-backed session save handler tests
 */
class DynamoDbSessionSaveHandlerFactoryTest extends BaseModuleTest
{
    public function testCanFetchSaveHandlerFromServiceManager()
    {
        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();
        $serviceManager     = $this->createServiceManagerForTest();

        $config = array(
            'aws' => array(
                'region' => 'us-east-1'
            ),
            'aws_zf2' => array(
                'session' => array(
                    'save_handler' => array(
                        'dynamodb' => array()
                    )
                )
            )
        );

        $serviceManager->setService(
            'Config',
            $config
        );

        /** @var $saveHandler \Aws\Session\SaveHandler\DynamoDb */
        $saveHandler = $serviceManager->get('Aws\Session\SaveHandler\DynamoDb');

        $this->assertInstanceOf('Aws\Session\SaveHandler\DynamoDb', $saveHandler);
    }

    /**
     * @expectedException Zend\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testExceptionThrownWhenSaveHandlerConfigurationDoesNotExist()
    {
        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();
        $serviceManager     = $this->createServiceManagerForTest();

        $serviceManager->setService('Config', array());

        /** @var $saveHandler \Aws\Session\SaveHandler\DynamoDb */
        $saveHandler = $serviceManager->get('Aws\Session\SaveHandler\DynamoDb');
    }
}
