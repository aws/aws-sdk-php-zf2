# AWS SDK ZF2 Module

## Introduction

This module provides a simple wrapper for the AWS SDK for PHP. It registers the AWS service builder as a service in the
ZF2 service manager, making it easily accessible anywhere in your application.

## Installation

Add your access keys and region to your local config file (e.g., `config/autoload/aws.local.php`):

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

## Links

* [AWS SDK for PHP on Github](http://github.com/aws/aws-sdk-php)
* [AWS SDK for PHP website](http://aws.amazon.com/sdkforphp/)
* [AWS on Packagist](https://packagist.org/packages/aws)
* [License](http://aws.amazon.com/apache2.0/)
* [ZF2 website](http://framework.zend.com)
