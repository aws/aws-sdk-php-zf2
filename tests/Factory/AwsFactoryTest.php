<?php

namespace AwsModule\Tests\Factory;

use AwsModule\Factory\AwsFactory;
use Aws\Sdk as AwsSdk;
use AwsModule\Module;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AWS Module test cases
 */
class AwsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanFetchAwsFromServiceManager()
    {
        $serviceLocator = $this->getMockServiceLocator();
        $awsFactory = new AwsFactory();

        /** @var $aws AwsSdk */
        $aws = $awsFactory->createService($serviceLocator);

        $this->assertInstanceOf(AwsSdk::class, $aws);
    }

    public function testProvidesVersionInformationForSdkUserAgent()
    {
        $config = [
            'aws_zf2' => [
                'zend_framework_version' => '3.0'
            ]
        ];

        $serviceLocator = $this->getMockServiceLocator();

        $serviceLocator->expects($this->at(0))->method('get')->with('Config')->willReturn($config);

        $awsFactory = new AwsFactory();
        $aws = $awsFactory->createService($serviceLocator);
        $argsProperty = (new \ReflectionClass($aws))->getProperty('args');
        $argsProperty->setAccessible(true);
        $args = $argsProperty->getValue($aws);


        $this->assertArrayHasKey('ua_append', $args);
        $this->assertInternalType('array', $args['ua_append']);
        $this->assertNotEmpty($args['ua_append']);
        $this->assertNotEmpty(array_filter($args['ua_append'], function ($ua) {
            return false !== strpos($ua, Module::VERSION);
        }));


        $this->assertNotEmpty(array_filter($args['ua_append'], function ($ua) use ($config) {
            return false !== strpos($ua, $config['aws_zf2']['zend_framework_version']);
        }));
    }

    private function getMockServiceLocator()
    {
        $serviceLocator = $this->getMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->willReturn([]);

        return $serviceLocator;
    }
}
