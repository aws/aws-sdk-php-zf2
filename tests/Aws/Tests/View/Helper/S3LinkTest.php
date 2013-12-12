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
use Aws\S3\S3Client;
use Aws\View\Helper\S3Link;

class S3LinkTest extends BaseModuleTest
{
    /**
     * @var S3Client
     */
    protected $s3Client;

    /**
     * @var S3Link
     */
    protected $viewHelper;

    public function setUp()
    {
        $this->s3Client = S3Client::factory(array(
            'key'    => '1234',
            'secret' => '5678'
        ));

        $this->viewHelper = new S3Link($this->s3Client);
    }

    public function testAssertUseSslByDefault()
    {
        $this->assertEquals('https', $this->viewHelper->getScheme());
    }

    /**
     * @expectedException \Aws\View\Exception\InvalidSchemeException
     */
    public function testAssertInvalidSchemesThrowExceptions()
    {
        $this->viewHelper->setScheme('nosuchscheme');
    }

    public function testGenerateSimpleLink()
    {
        $link = $this->viewHelper->__invoke('my-object', 'my-bucket');
        $this->assertEquals('https://my-bucket.s3.amazonaws.com/my-object', $link);
    }

    public function testGenerateSimpleNonSslLink()
    {
        $this->viewHelper->setUseSsl(false);

        $link = $this->viewHelper->__invoke('my-object', 'my-bucket');
        $this->assertEquals('http://my-bucket.s3.amazonaws.com/my-object', $link);
    }

    public function testGenerateSimpleProtocolRelativeLink()
    {
        $this->viewHelper->setScheme(null);

        $link = $this->viewHelper->__invoke('my-object', 'my-bucket');
        $this->assertEquals('//my-bucket.s3.amazonaws.com/my-object', $link);
    }

    public function testCanUseDefaultBucket()
    {
        $this->viewHelper->setDefaultBucket('my-default-bucket');

        $link = $this->viewHelper->__invoke('my-object');
        $this->assertEquals('https://my-default-bucket.s3.amazonaws.com/my-object', $link);
    }

    public function testAssertGivenBucketOverrideDefaultBucket()
    {
        $this->viewHelper->setDefaultBucket('my-default-bucket');

        $link = $this->viewHelper->__invoke('my-object', 'my-overriden-bucket');
        $this->assertEquals('https://my-overriden-bucket.s3.amazonaws.com/my-object', $link);
    }

    public function testCreatesUrlsForRegionalBuckets()
    {
        $this->s3Client->setRegion('sa-east-1');

        $link = $this->viewHelper->__invoke('my-object', 'my-bucket');
        $this->assertEquals('https://my-bucket.s3-sa-east-1.amazonaws.com/my-object', $link);
    }

    public function testCreatesUrlsForNonUrlCompatibleBucketNames()
    {
        $link = $this->viewHelper->__invoke('my-object', 'my.bucket');
        $this->assertEquals('https://s3.amazonaws.com/my.bucket/my-object', $link);
    }

    /**
     * @expectedException \Aws\View\Exception\InvalidDomainNameException
     */
    public function testFailsWhenNoBucketSpecified()
    {
        $link = $this->viewHelper->__invoke('my-object');
    }

    /**
     * @dataProvider dataForLinkSigningTest
     */
    public function testGenerateSignedLink($scheme)
    {
        $this->viewHelper->setScheme($scheme);
        $expires = time() + 10;

        $actualResult = $this->viewHelper->__invoke('my-object', 'my-bucket', $expires);

        // Build expected signature
        $request = $this->s3Client->get($this->viewHelper->__invoke('my-object', 'my-bucket'));
        $request->getParams()->set('s3.resource', '/my-bucket/my-object');
        $signature = $this->s3Client->getSignature();
        $signature = $signature->signString(
            $signature->createCanonicalizedString($request, $expires),
            $this->s3Client->getCredentials()
        );
        $expectedResult = sprintf(
            ltrim("{$scheme}://my-bucket.s3.amazonaws.com/my-object?AWSAccessKeyId=%s&Expires=%s&Signature=%s", ':'),
            $this->s3Client->getCredentials()->getAccessKeyId(),
            $expires,
            urlencode($signature)
        );

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function dataForLinkSigningTest()
    {
        return array(
            array('https'),
            array('http'),
            array(NULL),
        );
    }
}
