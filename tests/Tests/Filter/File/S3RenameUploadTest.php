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

namespace AwsTests\Filter\File;

use Aws\Filter\File\S3RenameUpload;
use Aws\S3\S3Client;
use Aws\Tests\BaseModuleTest;
use ReflectionMethod;

class S3RenameUploadTest extends BaseModuleTest
{
    /**
     * @var S3RenameUpload
     */
    protected $filter;

    public function setUp()
    {
        $s3Client = S3Client::factory(array(
            'key'    => '1234',
            'secret' => '5678'
        ));

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

        $this->filter->setOptions(array(
            'bucket' => '/my-bucket/'
        ));
        $this->assertEquals('my-bucket', $this->filter->getBucket());
    }

    public function testThrowExceptionIfNoBucketIsSet()
    {
        $this->setExpectedException('Aws\Filter\Exception\MissingBucketException');
        $this->filter->filter(array('tmp_name' => 'foo'));
    }

    /**
     * @dataProvider tmpNameProvider
     */
    public function testAssertS3UriIsGenerated($tmpName, $expectedKey)
    {
        $reflMethod = new ReflectionMethod($this->filter, 'getFinalTarget');
        $reflMethod->setAccessible(true);

        $this->filter->setBucket('my-bucket');

        $result = $reflMethod->invoke($this->filter, array(
            'tmp_name' => $tmpName
        ));

        $this->assertEquals("s3://my-bucket/{$expectedKey}", $result);
    }

    public function tmpNameProvider()
    {
        return array(
            array('temp/phptmpname', 'temp/phptmpname'),
            array('temp/phptmpname/', 'temp/phptmpname'),
            array('temp\\phptmpname', 'temp/phptmpname'),
            array('temp\\phptmpname\\', 'temp/phptmpname'),
        );
    }
}
