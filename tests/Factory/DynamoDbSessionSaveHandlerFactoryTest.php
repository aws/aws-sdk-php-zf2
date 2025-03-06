<?php

namespace AwsModule\Tests\Factory;

use Aws\DynamoDb\Exception\DynamoDbException;
use AwsModule\Factory\DynamoDbSessionSaveHandlerFactory;
use Aws\Sdk as AwsSdk;
use AwsModule\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * DynamoDB-backed session save handler tests
 */
class DynamoDbSessionSaveHandlerFactoryTest extends TestCase
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

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator->method('get')
            ->withConsecutive(
                ['Config'],
                [AwsSdk::class]
            )
            ->willReturnOnConsecutiveCalls(
                $config,
                $awsSdk
            );


        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();

        /** @var $saveHandler \AwsModule\Session\SaveHandler\DynamoDb */
        $saveHandler = $saveHandlerFactory->createService($serviceLocator);

        $this->assertInstanceOf(DynamoDbSaveHandler::class, $saveHandler);
    }

    public function testExceptionThrownWhenSaveHandlerConfigurationDoesNotExist()
    {
        $this->expectException(ServiceNotCreatedException::class);
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with('Config')->willReturn([]);

        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();

        $saveHandlerFactory->createService($serviceLocator);
    }
}
