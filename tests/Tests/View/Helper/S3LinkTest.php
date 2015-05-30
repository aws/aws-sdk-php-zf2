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

use Aws\S3\S3Client;
use AwsModule\View\Helper\S3Link;

class S3LinkTest extends \PHPUnit_Framework_TestCase
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
        $this->s3Client = new S3Client([
            'credentials' => [
                'key'    => '1234',
                'secret' => '5678'
            ],
            'region'  => 'us-east-1',
            'version' => 'latest'
        ]);

        $this->viewHelper = new S3Link($this->s3Client);
    }

    public function testGenerateSimpleLink()
    {
        $link = $this->viewHelper->__invoke('my-object', 'my-bucket');
        $this->assertEquals('https://s3.amazonaws.com/my-bucket/my-object', $link);
    }

    public function testCanUseDefaultBucket()
    {
        $this->viewHelper->setDefaultBucket('my-default-bucket');

        $link = $this->viewHelper->__invoke('my-object');
        $this->assertEquals('https://s3.amazonaws.com/my-default-bucket/my-object', $link);
    }

    public function testAssertGivenBucketOverrideDefaultBucket()
    {
        $this->viewHelper->setDefaultBucket('my-default-bucket');

        $link = $this->viewHelper->__invoke('my-object', 'my-overriden-bucket');
        $this->assertEquals('https://s3.amazonaws.com/my-overriden-bucket/my-object', $link);
    }

    public function testCreatesUrlsForRegionalBuckets()
    {
        $s3Client = new S3Client([
            'credentials' => [
                'key'    => '1234',
                'secret' => '5678'
            ],
            'region'  => 'sa-east-1',
            'version' => 'latest'
        ]);

        $viewHelper = new S3Link($s3Client);

        $link = $viewHelper->__invoke('my-object', 'my-bucket');
        $this->assertEquals('https://s3-sa-east-1.amazonaws.com/my-bucket/my-object', $link);
    }

    public function testCreatesUrlsForNonUrlCompatibleBucketNames()
    {
        $link = $this->viewHelper->__invoke('my-object', 'my.bucket');
        $this->assertEquals('https://s3.amazonaws.com/my.bucket/my-object', $link);
    }

    /**
     * @expectedException \AwsModule\View\Exception\InvalidDomainNameException
     */
    public function testFailsWhenNoBucketSpecified()
    {
        $link = $this->viewHelper->__invoke('my-object');
    }

    public function testGenerateSignedLink()
    {
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
            ltrim("https://my-bucket.s3.amazonaws.com/my-object?AWSAccessKeyId=%s&Expires=%s&Signature=%s", ':'),
            $this->s3Client->getCredentials()->getAccessKeyId(),
            $expires,
            urlencode($signature)
        );

        $this->assertEquals($expectedResult, $actualResult);
    }
}
