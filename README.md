# AWS SDK ZF2 Module

## Introduction

This module provides a simple wrapper for the AWS SDK for PHP. It registers the AWS service builder as a service in the
ZF2 service manager, making it easily accessible anywhere in your application.

## Installation

Install the module using Composer into your application's vendor directory. Add the following line to your
`composer.json`. This will also install the AWS SDK for PHP.

```json
{
    "require": {
        "aws/aws-sdk-php-zf2": "1.*"
    }
}
```

## Configuration

Enable the module in your `application.config.php` file.

```php
return array(
    'modules' => array(
        'Aws'
    )
);
```

Copy and paste the `aws.local.php.dist` file to your `config/autoload` folder and customize it with your credentials and
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

> NOTE: If you are using [IAM Instance Profile
credentials](http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/UsingIAM.html#UsingIAMrolesWithAmazonEC2Instances)
(also referred to as IAM Roles for instances), you can omit your `key` and `secret` parameters since they will be
fetched from the Amazon EC2 instance automatically.

## Usage

You can get the AWS service builder object from anywhere that the ZF2 service locator is available (e.g. controller
classes). The following example instantiates an Amazon DynamoDB client and creates a table in DynamoDb.

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

## View helpers

Starting from version 1.1, AWS ZF2 module now provides two view helpers to generate S3 and CloudFront links.

> Note: both view helpers generate a HTTPS URL by default. This is good for security, however please remember that
CloudFront charge more for HTTPS requests. You can turn off SSL by calling the `setUseSsl` on both helpers.

### S3

To create a S3 link in your view:

```php
<?php echo $this->s3Link('my-object', 'my-bucket');
```

The default bucket can be set globally by using the `setDefautBucket` method:

```php
<?php
    $this->s3Link->setDefaultBucket('my-bucket');
    echo $this->s3Link('my-object');
```

You can also create signed URLs by passing a third argument which is the expiration date:

```php
<?php echo $this->s3Link('my-object', 'my-bucket', '+10 minutes');
```

### CloudFront

To create CloudFront link in your view:

```php
<?php echo $this->cloudFrontLink('my-object', 'my-domain');
```

The default domain can be set globally by using the `setDefaultDomain` method:

```php
<?php
    $this->cloudFrontLink->setDefaultDomain('my-domain');
    echo $this->cloudFrontLink('my-object');
```

You can also create signed URLs by passing a third argument which is the expiration date:

```php
<?php echo $this->cloudFrontLink('my-object', 'my-bucket', time() + 60);
```

## Related Modules

The following are some ZF2 modules that are built on top of the AWS SDK for PHP using this module:

* [SlmMail](https://github.com/juriansluiman/SlmMail) - Module that allow to send emails with various providers (including Amazon SES)
* [SlmQueueSqs](https://github.com/juriansluiman/SlmQueueSqs) – Module that simplifies the use of Amazon SQS

## Links

* [AWS SDK for PHP on Github](http://github.com/aws/aws-sdk-php)
* [AWS SDK for PHP website](http://aws.amazon.com/sdkforphp/)
* [AWS on Packagist](https://packagist.org/packages/aws)
* [License](http://aws.amazon.com/apache2.0/)
* [ZF2 website](http://framework.zend.com)
