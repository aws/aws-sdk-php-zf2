# AWS SDK ZF2 Module
Version 0.1

## Introduction

This module provides a simple wrapper for the AWS PHP SDK library. It registers
the AWS service builder as a service in the service manager, making it easily
accessible anywhere in your application.

## Installation

Add your secret/public keys and region to your local config file
(`config/autoload/aws.local.php` for example):

```php
<?php
return array(
    // These are the minimum required settings for using the SDK
    'aws' => array(
        'key'    => 'change_me',
        'secret' => 'change_me',
        'region' => 'change_me'
    ),

    // Instead of defining settings in this file, you can provide a path to an AWS SDK for PHP config file
    // 'aws' => 'path/to/aws-config.php',
);
```

## Usage

```php
public function indexAction()
{
    $aws    = $this->getServiceLocator()->get('aws');
    $client = $aws->get('dynamodb');

    $table = 'posts';

    // Create a "posts" table
    $result = $client->createTable(array(
        'TableName' => $table,
        'KeySchema' => array(
            'HashKeyElement' => array(
                'AttributeName' => 'slug',
                'AttributeType' => 'S'
            )
        ),
        'ProvisionedThroughput' => array(
            'ReadCapacityUnits'  => 10,
            'WriteCapacityUnits' => 5
        )
    ));

    // Wait until the table is created and active
    $client->waitUntil('TableExists', array('TableName' => $table));

    echo "The {$table} table has been created.\n";
}
```

See the full PHP SDK documentation [here](http://docs.aws.amazon.com/awssdkdocsphp2/latest/gettingstartedguide/sdk-php2-using-the-sdk.html).
