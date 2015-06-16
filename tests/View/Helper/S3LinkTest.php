<?php

namespace AwsModule\Tests\View\Helper;

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
        $s3Client = new S3Client([
            'credentials' => [
                'key'    => '1234',
                'secret' => '5678',
                'token'  => '999'
            ],
            'region'  => 'sa-east-1',
            'version' => 'latest'
        ]);

        $viewHelper = new S3Link($s3Client);

        $url = $viewHelper->__invoke('my-object', 'my-bucket', $expires);

        $this->assertStringStartsWith('https://s3-sa-east-1.amazonaws.com/my-bucket/my-object?', $url);
        $this->assertContains('X-Amz-Security-Token=999', $url);
        $this->assertContains('X-Amz-Content-Sha256=', $url);
        $this->assertContains('X-Amz-Expires=', $url);
    }
}
