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

namespace AwsModuleTests\Filter\File;

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
        $this->filter->filter(['tmp_name' => 'foo']);
    }

    /**
     * @dataProvider tmpNameProvider
     */
    public function testAssertS3UriIsGenerated($tmpName, $expectedKey)
    {
        $reflMethod = new ReflectionMethod($this->filter, 'getFinalTarget');
        $reflMethod->setAccessible(true);

        $this->filter->setBucket('my-bucket');

        $result = $reflMethod->invoke($this->filter, [
            'tmp_name' => $tmpName
        ]);

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
