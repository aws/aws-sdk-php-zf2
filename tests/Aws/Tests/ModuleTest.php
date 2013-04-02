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

use Aws\Module as AwsModule;

/**
 * AWS Module test cases
 */
class ModuleTest extends BaseModuleTest
{
    /**
     * Tests that getConfig is returning valid data for the module
     */
    public function testConfigIsReturnedAsArray()
    {
        $module = new AwsModule();
        $config = $module->getConfig();

        $this->assertInternalType('array', $config);

        $classExists = isset($config['service_manager']['factories']['Aws'])
            && class_exists($config['service_manager']['factories']['Aws']);

        $this->assertTrue($classExists);
    }

    /**
     * Tests a normal module registration
     */
    public function testRegisterAwsModule()
    {
        // Create the module and service manager, and register the module
        $serviceManager = $this->createServiceManagerForTest();
        $serviceManager->setService('config', array('aws' => array(
            'key'    => 'your-aws-access-key-id',
            'secret' => 'your-aws-secret-access-key',
        )));

        // Make sure the service manager received the service configuration from the module
        $services = $serviceManager->getRegisteredServices();
        $this->assertContains('aws', $services['factories']);

        // Get the SDK's service builder from the ZF2's service manager
        $aws = $serviceManager->get('aws');
        $this->assertInstanceOf('Guzzle\Service\Builder\ServiceBuilderInterface', $aws);

        // Get an instance of a client (S3) to use for testing
        $s3 = $aws->get('s3');
        $this->assertInstanceOf('Aws\S3\S3Client', $s3);

        // Verify that the S3 client created by the SDK received the provided credentials
        $this->assertEquals('your-aws-access-key-id', $s3->getCredentials()->getAccessKeyId());
        $this->assertEquals('your-aws-secret-access-key', $s3->getCredentials()->getSecretKey());

        // Make sure the user agent contains "ZF2"
        $command = $s3->getCommand('ListBuckets');
        $request = $command->prepare();
        $s3->dispatch('command.before_send', array('command' => $command));
        $this->assertRegExp('/.+ZF2\/.+/', $request->getHeader('User-Agent', true));
    }

    /**
     * Tests modules registration with no config provided
     *
     * @dataProvider dataForNoConfigTest
     * @expectedException \Aws\Common\Exception\InstanceProfileCredentialsException
     */
    public function testNoConfigProvided(array $providedConfig)
    {
        // Create the module and service manager, and register the module without any configuration
        $serviceManager = $this->createServiceManagerForTest();
        $serviceManager->setService('config', $providedConfig);

        // Instantiate a client and get the access key, which should trigger an exception trying to use IAM credentials
        $s3 = $serviceManager->get('aws')->get('s3');
        $s3->getCredentials()->getAccessKeyId();
    }

    /**
     * @return array
     */
    public function dataForNoConfigTest()
    {
        return array(
            array(array()),
            array(array('aws' => array())),
        );
    }
}
