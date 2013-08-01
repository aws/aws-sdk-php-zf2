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

namespace Aws\Session\SaveHandler;

use Aws\Dynamodb\Session\SessionHandler;
use Zend\Session\SaveHandler\SaveHandlerInterface;

/**
 * DynamoDB session save handler
 */
class DynamoDb implements SaveHandlerInterface
{
    /**
     * @var SessionHandler
     */
    protected $sessionHandler;

    /**
     * Constructor
     *
     * @param SessionHandler $sessionHandler DynamoDB session handler
     */
    public function __construct(SessionHandler $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * Open a session for writing. Triggered by session_start()
     *
     * Part of the standard PHP session handler interface
     *
     * @param  string $savePath Session save path
     * @param  string $name     Session name
     *
     * @return bool Whether or not the operation succeeded
     */
    public function open($savePath, $name)
    {
        return $this->sessionHandler->open($savePath, $name);
    }

    /**
     * Close a session
     *
     * Part of the standard PHP session handler interface
     *
     * @return bool Whether or not the operation succeeded
     */
    public function close()
    {
        return $this->sessionHandler->close();
    }

    /**
     * Read session data stored in DynamoDB
     *
     * Part of the standard PHP session handler interface
     *
     * @param string $id The session ID
     *
     * @return string Session data
     */
    public function read($id)
    {
        return $this->sessionHandler->read($id);
    }

    /**
     * Write session data to DynamoDB
     *
     * Part of the standard PHP session handler interface
     *
     * @param string $id   The session ID
     * @param string $data The serialized session data
     *
     * @return bool Whether or not the operation succeeded
     */
    public function write($id, $data)
    {
        return $this->sessionHandler->write($id, $data);
    }

    /**
     * Destroy a session stored in DynamoDB
     *
     * Part of the standard PHP session handler interface
     *
     * @param string $id The session ID
     *
     * @return bool Whether or not the operation succeeded
     */
    public function destroy($id)
    {
        return $this->sessionHandler->destroy($id);
    }

    /**
     * Trigger garbage collection on expired sessions
     *
     * Part of the standard PHP session handler interface
     *
     * @param int $maxlifetime The value of `session.gc_maxlifetime`. Ignored.
     *
     * @return bool Whether or not the operation succeeded
     */
    public function gc($maxlifetime)
    {
        return $this->sessionHandler->gc($maxlifetime);
    }
}
