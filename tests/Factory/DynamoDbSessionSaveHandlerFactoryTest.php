<?php

namespace AwsModule\Tests\Factory;

use AwsModule\Factory\DynamoDbSessionSaveHandlerFactory;
use Aws\Sdk as AwsSdk;
use AwsModule\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Laminas\ServiceManager\ServiceLocatorInterface;

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

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->at(0))->method('get')->with('Config')->willReturn($config);
        $serviceLocator->expects($this->at(1))->method('get')->with(AwsSdk::class)->willReturn($awsSdk);


        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();

        /** @var $saveHandler \AwsModule\Session\SaveHandler\DynamoDb */
        $saveHandler = $saveHandlerFactory->createService($serviceLocator);

        $this->assertInstanceOf(DynamoDbSaveHandler::class, $saveHandler);
    }

    /**
     * @expectedException \Laminas\ServiceManager\Exception\ServiceNotCreatedException
     */
    public function testExceptionThrownWhenSaveHandlerConfigurationDoesNotExist()
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator->expects($this->once())->method('get')->with('Config')->willReturn([]);

        $saveHandlerFactory = new DynamoDbSessionSaveHandlerFactory();

        $saveHandlerFactory->createService($serviceLocator);
    }
}
