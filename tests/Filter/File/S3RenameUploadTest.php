<?php

namespace AwsModule\Tests\Filter\File;

use AwsModule\Filter\Exception\MissingBucketException;
use AwsModule\Filter\File\S3RenameUpload;
use Aws\S3\S3Client;
use ReflectionMethod;

class S3RenameUploadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var S3RenameUpload
     */
    protected $filter;

    public function setUp()
    {
        $s3Client = new S3Client([
            'credentials' => [
                'key'    => '1234',
                'secret' => '5678'
            ],
            'region'  => 'us-east-1',
            'version' => 'latest'
        ]);

        $this->filter = new S3RenameUpload($s3Client);
    }

    public function testAssertFilterAlwaysRegistersS3StreamWrapper()
    {
        $this->assertContains('s3', stream_get_wrappers());
    }

    public function testBucketNameIsTrimmedWhenSet()
    {
        $this->filter->setBucket('/my-bucket/');
        $this->assertEquals('my-bucket', $this->filter->getBucket());

        $this->filter->setOptions([
            'bucket' => '/my-bucket/'
        ]);
        $this->assertEquals('my-bucket', $this->filter->getBucket());
    }

    public function testThrowExceptionIfNoBucketIsSet()
    {
        $this->setExpectedException(MissingBucketException::class);
        $this->filter->filter(['tmp_name' => 'foo', 'name' => 'foo']);
    }

    /**
     * @dataProvider tmpNameProvider
     */
    public function testAssertS3UriIsGenerated($tmpName, $expectedKey)
    {
        $reflMethod = new ReflectionMethod($this->filter, 'getFinalTarget');
        $reflMethod->setAccessible(true);

        $this->filter->setBucket('my-bucket');

        $result = $reflMethod->invokeArgs($this->filter, [$tmpName, $tmpName]);

        $this->assertEquals("s3://my-bucket/{$expectedKey}", $result);
    }

    public function tmpNameProvider()
    {
        return [
            ['temp/phptmpname', 'temp/phptmpname'],
            ['temp/phptmpname/', 'temp/phptmpname'],
            ['temp\\phptmpname', 'temp/phptmpname'],
            ['temp\\phptmpname\\', 'temp/phptmpname'],
        ];
    }
}
