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

namespace AwsTests\View\Helper;

use Aws\Tests\BaseModuleTest;
use Aws\CloudFront\CloudFrontClient;
use Aws\View\Helper\CloudFrontLink;

class CloudFrontLinkTest extends BaseModuleTest
{
    /**
     * @var CloudFrontClient
     */
    protected $cloudFrontLink;

    /**
     * @var CloudFrontLink
     */
    protected $viewHelper;

    public function setUp()
    {
        $this->cloudFrontLink = CloudFrontClient::factory(array(
            'key'         => '1234',
            'secret'      => '5678',
            'key_pair_id' => 'my_key_pair_id',
            'private_key' => 'my_private_key'
        ));

        $this->viewHelper = new CloudFrontLink($this->cloudFrontLink);
    }

    public function testAssertDoesUseSslByDefault()
    {
        $this->assertTrue($this->viewHelper->getUseSsl());
    }

    public function testGenerateSimpleLink()
    {
        $link = $this->viewHelper->__invoke('my-object', 'my-domain');
        $this->assertEquals('https://my-domain.cloudfront.net/my-object', $link);
    }

    public function testGenerateSimpleNonSslLink()
    {
        $this->viewHelper->setUseSsl(false);

        $link = $this->viewHelper->__invoke('my-object', 'my-domain');
        $this->assertEquals('http://my-domain.cloudfront.net/my-object', $link);
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
}
