CHANGELOG
=========

## 4.3.0 - 2020-10-29

* Migrated to the [Laminas project](https://www.zend.com/blog/evolution-zend-framework-laminas-project)

## 4.2.0 - 2019-08-05

* Relaxes Zend framework package constraints
* Added compatibility for `zend-filter` 2.9

## 4.1.0 - 2018-02-13

* Relaxes the version constraint on `zend-session`.

## 4.0.0 - 2016-12-13

* Adds support for ZF3, requires PHP version >= 5.6

## 3.0.0 - 2016-07-22

* Fix composer dependency for ZF2 service manager to 2.7.* | 3.*
* Update README.md with instructions.

## 2.0.1 - 2015-08-05

* Added a makefile to build releases

## 2.0.0

* [BC] PHP minimum version is now 5.5
* [BC] Now require Aws SDK v3
* [BC] To avoid name clashes, module name has been renamed from `Aws` to `AwsModule`.

## 1.2.0

* Added the ability to create protocol-relative URLs with the S3 and CloudFront link view helpers
* Added TravisCI configuration
* Added PHPUnit as a development dependency

## 1.1.0

* Added ZF2 session save handler for Amazon DynamoDB
* This module is now following [semver](http://semver.org/)

## 1.0.3

* Added a file filter to upload to Amazon S3
* Added the ability to change the hostname for the Amazon CloudFront link view helper

## 1.0.2

* Added Amazon S3 and Amazon CloudFront view helpers for generating links

## 1.0.1

* Refactored module architecture

## 1.0.0

* Initial release
