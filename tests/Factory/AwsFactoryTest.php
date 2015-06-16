<?php

namespace AwsModule\Tests\Factory;

use AwsModule\Factory\AwsFactory;
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
