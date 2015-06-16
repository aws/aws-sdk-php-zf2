<?php

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
