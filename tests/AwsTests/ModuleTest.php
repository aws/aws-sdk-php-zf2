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

namespace AwsTests;

use Aws\Module as AwsModule;
use Zend\ServiceManager\Config as ServiceConfig;
use Zend\ServiceManager\ServiceManager;

/**
 * AWS Module test cases
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterAwsModule()
    {
        // Create the module and service manager, and register the module
        $module = new AwsModule();
        $config = $module->getConfig();

        $serviceManager = new ServiceManager(new ServiceConfig($config['service_manager']));
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
     * @expectedException \Aws\Common\Exception\InstanceProfileCredentialsException
     */
    public function testNoConfigProvided()
    {
        // Create the module and service manager, and register the module without any configuration
        $module = new AwsModule();
        $config = $module->getConfig();

        $serviceConfig  = new ServiceConfig($config['service_manager']);
        $serviceManager = new ServiceManager($serviceConfig);

        // Instantiate a client and get the access key, which should trigger an exception trying to use IAM credentials
        $s3 = $serviceManager->get('aws')->get('s3');
        $s3->getCredentials()->getAccessKeyId();
    }
}
