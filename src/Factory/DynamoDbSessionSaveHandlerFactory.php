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

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\SessionHandler;
use AwsModule\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a DynamoDB-backed session save handler
 */
class DynamoDbSessionSaveHandlerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return DynamoDbSaveHandler
     * @throws ServiceNotCreatedException if "dynamodb" configuration is not set up correctly
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['aws_zf2']['session']['save_handler']['dynamodb'])) {
            throw new ServiceNotCreatedException(
                'ZF2 AWS PHP SDK configuration is missing a "dynamodb" key. ' .
                'Have you copied "config/aws_zf2.local.php.dist" into your ' .
                'project (without the .dist extension)?'
            );
        }

        /** @var AwsSdk $awsSdk */
        $awsSdk = $serviceLocator->get(AwsSdk::class);

        $saveHandlerConfig = $config['aws_zf2']['session']['save_handler']['dynamodb'];
        $sessionHandler    = SessionHandler::fromClient($awsSdk->createDynamoDb(), $saveHandlerConfig);

        return new DynamoDbSaveHandler($sessionHandler);
    }
}
