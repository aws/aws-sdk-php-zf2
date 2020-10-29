# AWS SDK ZF2 Module

[![Latest Stable Version](https://poser.pugx.org/aws/aws-sdk-php-zf2/v/stable.png)](https://packagist.org/packages/aws/aws-sdk-php-zf2)
[![Total Downloads](https://poser.pugx.org/aws/aws-sdk-php-zf2/downloads.png)](https://packagist.org/packages/aws/aws-sdk-php-zf2)
[![Build Status](https://travis-ci.org/aws/aws-sdk-php-zf2.png)](https://travis-ci.org/aws/aws-sdk-php-zf2)

This module provides a simple wrapper for the AWS SDK for PHP. It registers the AWS service builder as a service in the
ZF2 service manager, making it easily accessible anywhere in your application.

Jump To:
* [Getting Started](_#Getting-Started_)
* [Getting Help](_#Getting-Help_)
* [Contributing](_#Contributing_)
* [Related Modules](_#Related-Modules_)
* [More Resources](_#Resources_)

## Getting Started

### Installation

Install the module using Composer into your application's vendor directory. Add the following line to your
`composer.json`. This will also install the AWS SDK for PHP.

If you want to use `ZF3` and your PHP version >= 5.6, use

```json
{
    "require": {
        "aws/aws-sdk-php-zf2": "4.*"
    }
}
```

Otherwise,

```json
{
    "require": {
        "aws/aws-sdk-php-zf2": "3.*"
    }
}
```
> If you are using ZF2 service manager < 2.7, please use the 2.0.* version.

> If you are using AWS SDK v2, please use the 1.2.* version of the ZF2 module.

### Configuration

Add the module name to your project's `config/application.config.php` or `config/modules.config.php`:

```php
return array(
    /* ... */
    'modules' => array(
        /* ... */
        'AwsModule'
    ),
    /* ... */
);
```


Copy and paste the `aws.local.php.dist` file to your `config/autoload` folder and customize it with your credentials and
other configuration settings. Make sure to remove `.dist` from your file. Your `aws.local.php` might look something like
the following:

```php
<?php

return [
    'aws' => [
        'credentials' => [
            'key'    => '<your-aws-access-key-id>',
            'secret' => '<your-aws-secret-access-key>',
        ]
        'region' => 'us-west-2'
    ]
];
```

> NOTE: If you are using [IAM Instance Profile
credentials](http://docs.aws.amazon.com/AWSEC2/latest/UserGuide/UsingIAM.html#UsingIAMrolesWithAmazonEC2Instances)
(also referred to as IAM Roles for instances), you can omit your `key` and `secret` parameters since they will be
fetched from the Amazon EC2 instance automatically.

### Usage

You can get the AWS service builder object from anywhere that the ZF2 service locator is available (e.g. controller
classes). The following example instantiates an Amazon DynamoDB client and creates a table in DynamoDB.

```php
use Aws\Sdk;

public function indexAction()
{
    $aws    = $this->getServiceLocator()->get(Sdk::class);
    $client = $aws->createDynamoDb();

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

### View Helpers

The AWS SDK ZF2 Module now provides two view helpers to generate links for Amazon S3 and Amazon CloudFront resources.

> **Note:** Starting from v2 of the AWS module, all URLs for both S3 and CloudFront are using HTTPS and this cannot
be modified.

#### S3Link View Helper

To create a S3 link in your view:

```php
<?php echo $this->s3Link('my-object', 'my-bucket');
```

The default bucket can be set globally by using the `setDefaultBucket` method:

```php
<?php
    $this->plugin('s3Link')->setDefaultBucket('my-bucket');
    echo $this->s3Link('my-object');
```

You can also create signed URLs for private content by passing a third argument which is the expiration date:

```php
<?php echo $this->s3Link('my-object', 'my-bucket', '+10 minutes');
```

#### CloudFrontLink View Helper

To create CloudFront link in your view:

```php
<?php echo $this->cloudFrontLink('my-object', 'my-domain');
```

The default domain can be set globally by using the `setDefaultDomain` method:

```php
<?php
    $this->plugin('cloudFrontLink')->setDefaultDomain('my-domain');
    echo $this->cloudFrontLink('my-object');
```

You can also create signed URLs for private content by passing a third argument which is the expiration date:

```php
<?php echo $this->cloudFrontLink('my-object', 'my-bucket', time() + 60);
```

### Filters

The AWS SDK ZF2 module provides a simple file filter that allow to directly upload to S3.
The `S3RenameUpload` extends `RenameUpload` class, so please refer to [its
documentation](http://framework.zend.com/manual/2.2/en/modules/zend.filter.file.rename-upload.html#zend-filter-file-rename-upload)
for available options.

This filter only adds one option to set the bucket name (through the `setBucket` method, or by passing a `bucket` key
to the filter's `setOptions` method).

```php
$request = new Request();
$files   = $request->getFiles();
// e.g., $files['my-upload']['tmp_name'] === '/tmp/php5Wx0aJ'
// e.g., $files['my-upload']['name'] === 'profile-picture.jpg'

// Fetch the filter from the Filter Plugin Manager to automatically handle dependencies
$filter = $serviceLocator->get('FilterManager')->get('S3RenameUpload');

$filter->setOptions(’[
    'bucket'    => 'my-bucket',
    'target'    => 'users/5/profile-picture.jpg',
    'overwrite' => true
]);

$filter->filter($files['my-upload']);

// File has been renamed and moved to 'my-bucket' bucket, inside the 'users/5' path
```

### Session Save Handlers

Read the [session save handler section]
(http://zf2.readthedocs.org/en/latest/modules/zend.session.save-handler.html) in
the ZF2 documentation for more information.

#### DynamoDB

To follow the [ZF2 examples]
(http://zf2.readthedocs.org/en/latest/modules/zend.session.save-handler.html),
the DynamoDB session save handler might be used like this:

```php
use AwsModule\Session\SaveHandler\DynamoDb as DynamoDbSaveHandler;
use Laminas\Session\SessionManager;

// Assume we are in a context where $serviceLocator is a ZF2 service locator.

$saveHandler = $serviceLocator->get(DynamoDbSaveHandler::class);

$manager = new SessionManager();
$manager->setSaveHandler($saveHandler);
```

You will probably want to further configure the save handler, which you can do in your application. You can copy the
`config/aws_zf2.local.php.dist` file into your project's `config/autoload` directory (without the `.dist` of course).

See `config/aws_zf2.local.php.dist` and [the AWS session handler documentation]
(http://docs.aws.amazon.com/aws-sdk-php-2/latest/class-Aws.DynamoDb.Session.SessionHandler.html#_factory) for more
detailed configuration information.

## Getting Help

Please use these community resources for getting help. We use the GitHub issues for tracking bugs and feature requests and have limited bandwidth to address them.

* Ask a question on [StackOverflow](https://stackoverflow.com/) and tag it with [`aws-php-sdk`](http://stackoverflow.com/questions/tagged/aws-php-sdk)
* Come join the AWS SDK for PHP [gitter](https://gitter.im/aws/aws-sdk-php)
* Open a support ticket with [AWS Support](https://console.aws.amazon.com/support/home/)
* If it turns out that you may have found a bug, please [open an issue](https://github.com/aws/aws-sdk-php-zf2/issues/new/choose)

This SDK implements AWS service APIs. For general issues regarding the AWS services and their limitations, you may also take a look at the [Amazon Web Services Discussion Forums](https://forums.aws.amazon.com/).

### Opening Issues

If you encounter a bug with `aws-sdk-php-zf2` we would like to hear about it. Search the existing issues and try to make sure your problem doesn’t already exist before opening a new issue. It’s helpful if you include the version of `aws-sdk-php-zf2`, PHP version and OS you’re using. Please include a stack trace and reduced repro case when appropriate, too.

The GitHub issues are intended for bug reports and feature requests. For help and questions with using `aws-sdk-php` please make use of the resources listed in the Getting Help section. There are limited resources available for handling issues and by keeping the list of open issues lean we can respond in a timely manner.

## Contributing

We work hard to provide a high-quality and useful SDK for our AWS services, and we greatly value feedback and contributions from our community. Please review our [contributing guidelines](./CONTRIBUTING.md) before submitting any issues or pull requests to ensure we have all the necessary information to effectively respond to your bug report or contribution.

## Related Modules

The following are some ZF2 modules that use the AWS SDK for PHP by including this module:

* [SlmMail](https://github.com/juriansluiman/SlmMail) - Module that allow to send emails with various providers
  (including Amazon SES)
* [SlmQueueSqs](https://github.com/juriansluiman/SlmQueueSqs) – Module that simplifies the use of Amazon SQS

## Resources

* [AWS SDK for PHP on Github](http://github.com/aws/aws-sdk-php)
* [AWS SDK for PHP website](http://aws.amazon.com/sdkforphp/)
* [AWS on Packagist](https://packagist.org/packages/aws)
* [License](http://aws.amazon.com/apache2.0/)
* [Laminas (ZF2) website](https://getlaminas.org/)
