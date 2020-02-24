<?php

namespace AwsModule\Factory;

use Aws\Sdk as AwsSdk;
use Aws\DynamoDb\SessionHandler;
use AwsModule\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Factory used to instantiate a DynamoDB-backed session save handler
 */
class DynamoDbSessionSaveHandlerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return DynamoDbSaveHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');

        if (!isset($config['aws_zf2']['session']['save_handler']['dynamodb'])) {
            throw new ServiceNotCreatedException(
                'ZF2 AWS PHP SDK configuration is missing a "dynamodb" key. ' .
                'Have you copied "config/aws_zf2.local.php.dist" into your ' .
                'project (without the .dist extension)?'
            );
        }

        /** @var AwsSdk $awsSdk */
        $awsSdk = $container->get(AwsSdk::class);

        $saveHandlerConfig = $config['aws_zf2']['session']['save_handler']['dynamodb'];
        $sessionHandler    = SessionHandler::fromClient($awsSdk->createDynamoDb(), $saveHandlerConfig);

        return new DynamoDbSaveHandler($sessionHandler);
    }

    /**
     * {@inheritDoc}
     * @return DynamoDbSaveHandler
     * @throws ServiceNotCreatedException if "dynamodb" configuration is not set up correctly
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, DynamoDbSaveHandler::class);
    }
}
