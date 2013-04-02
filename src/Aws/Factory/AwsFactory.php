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

namespace Aws\Factory;

use Aws\Common\Aws;
use Aws\Common\Client\UserAgentListener;
use Guzzle\Common\Event;
use Guzzle\Service\Client;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Version\Version;

/**
 * Factory used to instantiate an AWS client
 */
class AwsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return Aws
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        // Instantiate the AWS SDK for PHP
        $config = $serviceLocator->get('Config');
        $config = isset($config['aws']) ? $config['aws'] : array();
        $aws    = Aws::factory($config);

        // Attach an event listener that will append the ZF2 version number in the user agent string
        $aws->getEventDispatcher()->addListener('service_builder.create_client', array($this, 'onCreateClient'));

        return $aws;
    }

    /**
     * Add ZF2 version in UserAgent (mainly for statistics purpose on Amazon side)
     *
     * @param  Event $event
     * @return void
     */
    public function onCreateClient(Event $event)
    {
        $clientConfig  = $event['client']->getConfig();
        $commandParams = $clientConfig->get(Client::COMMAND_PARAMS) ?: array();
        $clientConfig->set(Client::COMMAND_PARAMS, array_merge_recursive($commandParams, array(
            UserAgentListener::OPTION => 'ZF2/' . Version::VERSION,
        )));
    }
}
