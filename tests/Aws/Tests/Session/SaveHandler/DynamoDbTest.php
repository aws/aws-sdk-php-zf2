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

namespace AwsTests\Session\SaveHandler;

use Aws\DynamoDb\Session\SessionHandler;
use Aws\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Aws\Tests\BaseModuleTest;

class DynamoDbTest extends BaseModuleTest
{
    /**
     * @var SessionHandler
     */
    protected $sessionHandler;

    /**
     * @var DynamoDbSaveHandler
     */
    protected $saveHandler;

    public function setUp()
    {
        parent::setUp();

        $this->sessionHandler = $this->getMock(
            'Aws\DynamoDb\Session\SessionHandler',
            array(
                'open',
                'close',
                'read',
                'write',
                'destroy',
                'gc',
            ),
            array(),
            '',
            false
        );

        $this->saveHandler = new DynamoDbSaveHandler($this->sessionHandler);
    }

    public function testSessionHandlerOpenIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('open')
            ->with($this->equalTo('mypath'), $this->equalTo('myname'))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->open('mypath', 'myname');

        $this->assertTrue($result);
    }

    public function testSessionHandlerCloseIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('close')
            ->with()
            ->will($this->returnValue(true));

        $result = $this->saveHandler->close();

        $this->assertTrue($result);
    }

    public function testSessionHandlerReadIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('read')
            ->with($this->equalTo('myid'))
            ->will($this->returnValue('mydata'));

        $result = $this->saveHandler->read('myid');

        $this->assertEquals('mydata', $result);
    }

    public function testSessionHandlerWriteIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('write')
            ->with($this->equalTo('myid'), $this->equalTo('mydata'))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->write('myid', 'mydata');

        $this->assertTrue($result);
    }

    public function testSessionHandlerDestroyIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('destroy')
            ->with($this->equalTo('myid'))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->destroy('myid');

        $this->assertTrue($result);
    }

    public function testSessionHandlerGcIsCalled()
    {
        $this->sessionHandler->expects($this->once())
            ->method('gc')
            ->with($this->equalTo(420))
            ->will($this->returnValue(true));

        $result = $this->saveHandler->gc(420);

        $this->assertTrue($result);
    }
}
