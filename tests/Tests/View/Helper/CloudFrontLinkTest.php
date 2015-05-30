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

namespace AwsModuleTests\View\Helper;

use Aws\CloudFront\CloudFrontClient;
use AwsModule\View\Helper\CloudFrontLink;

class CloudFrontLinkTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CloudFrontClient
     */
    protected $cloudFrontClient;

    /**
     * @var CloudFrontLink
     */
    protected $viewHelper;

    public function setUp()
    {
        $this->cloudFrontClient = new CloudFrontClient([
            'credentials' => [
                'key'    => '1234',
                'secret' => '5678',
            ],
            'region'  => 'us-east-1',
            'version' => 'latest'
        ]);

        $this->viewHelper = new CloudFrontLink($this->cloudFrontClient);
    }

    public function testGenerateSimpleLink()
    {
        $link = $this->viewHelper->__invoke('my-object', 'my-domain');
        $this->assertEquals('https://my-domain.cloudfront.net/my-object', $link);
    }

    public function testCanUseDefaultDomain()
    {
        $this->viewHelper->setDefaultDomain('my-default-domain');

        $link = $this->viewHelper->__invoke('my-object');
        $this->assertEquals('https://my-default-domain.cloudfront.net/my-object', $link);
    }

    public function testAssertGivenDomainOverrideDefaultDomain()
    {
        $this->viewHelper->setDefaultDomain('my-default-domain');

        $link = $this->viewHelper->__invoke('my-object', 'my-overriden-domain');
        $this->assertEquals('https://my-overriden-domain.cloudfront.net/my-object', $link);
    }

    public function testCanTrimCloudFrontPartInDomain()
    {
        $link = $this->viewHelper->__invoke('my-object', '123abc.cloudfront.net');
        $this->assertEquals('https://123abc.cloudfront.net/my-object', $link);

        $link = $this->viewHelper->__invoke('my-object', '123abc.cloudfront.net/');
        $this->assertEquals('https://123abc.cloudfront.net/my-object', $link);
    }

    public function testCanUseCustomHostname()
    {
        $this->viewHelper->setHostname('example.com');
        $this->assertEquals('.example.com', $this->viewHelper->getHostname());

        $link = $this->viewHelper->__invoke('my-object', '123abc');
        $this->assertEquals('https://123abc.example.com/my-object', $link);
    }

    /**
     * @expectedException \AwsModule\View\Exception\InvalidDomainNameException
     */
    public function testFailsWhenDomainIsInvalid()
    {
        $this->viewHelper->setDefaultDomain('');
        $link = $this->viewHelper->__invoke('my-object');
    }

    public function testGenerateSignedLink()
    {
        if (!extension_loaded('openssl')) {
            $this->markTestSkipped('OpenSSL is required for this test.');
        }

        $pemFile = sys_get_temp_dir() . '/aws-sdk-php-zf2-cloudfront-test.pem';
        if (!file_exists($pemFile)) {
            // Generate a new Certificate Signing Request and public/private keypair
            $csr = openssl_csr_new(array(), $keypair);

            // Create a self-signed certificate
            $x509 = openssl_csr_sign($csr, null, $keypair, 1);
            openssl_x509_export($x509, $certificate);

            // Create and save a private key
            $privateKey = openssl_get_privatekey($keypair);
            openssl_pkey_export_to_file($privateKey, $pemFile);
        }

        $this->viewHelper->setHostname('example.com');
        $link = $this->viewHelper->__invoke('my-object', '123abc', time() + 600, 'kpid', $pemFile);
        $this->assertRegExp(
            '#^https\:\/\/123abc\.example\.com\/my-object\?Expires\=(.*)\&Signature\=(.*)\&Key-Pair-Id\=kpid$#',
            $link
        );
    }
}
