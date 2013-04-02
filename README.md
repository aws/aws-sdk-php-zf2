# AWS SDK ZF2 Module

## Introduction

This module provides a simple wrapper for the AWS SDK for PHP. It registers the AWS service builder as a service in the
ZF2 service manager, making it easily accessible anywhere in your application.

## Installation

Enable the module in your ``application.config.php`` file:

```php
return array(
    'modules' => array(
        'Aws'
    )
);
```

Copy-paste the file `aws.local.php.dist` to your `config/autoload` folder and customize it with your credentials and
other configuration settings. Make sure to remove `.dist` from your file. Your `aws.local.php` might look something like
the following:

```php
<?php

return array(
    'aws' => array(
        'key'    => '<your-aws-access-key-id>',
        'secret' => '<your-aws-secret-access-key>',
        'region' => 'us-west-2'
    )
);
```

## Usage

You can get the AWS service builder from anywhere that the ZF2 service locator is available (e.g. controller classes).

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
    $client->waitUntilTableExists(array('TableName' => $table));

    echo "The {$table} table has been created.\n";
}
```

## Third-parties modules

Here are some ZF2 modules that are built on top of the AWS SDK for PHP using this module:

* [SlmQueueSqs](https://github.com/juriansluiman/SlmQueueSqs) – Module that simplifies the use of Amazon SQS

## Links

* [AWS SDK for PHP on Github](http://github.com/aws/aws-sdk-php)
* [AWS SDK for PHP website](http://aws.amazon.com/sdkforphp/)
* [AWS on Packagist](https://packagist.org/packages/aws)
* [License](http://aws.amazon.com/apache2.0/)
* [ZF2 website](http://framework.zend.com)
