# Contributing to the AWS SDK ZF2 Module

We work hard to provide a high-quality and useful SDK. We greatly value feedback and contributions from the ZF2
community on this module and recognize your expertise. We welcome the submission of [issues][] and [pull requests][].

## What you should keep in mind

1. The AWS SDK for PHP and the AWS SDK ZF2 Module are released under the [Apache license][license]. Any code you submit
   will be released under that license. For substantial contributions, we may ask you to sign a [Contributor
   License Agreement (CLA)][cla].
2. We follow the [PSR-0][], [PSR-1][], and [PSR-2][] recommendations from the [PHP Framework Interop Group][php-fig].
   Please submit code that follows these standards. The [PHP CS Fixer][cs-fixer] tool can be helpful for formatting your
   code.
3. We maintain a high percentage of code coverage in our unit tests. If you make changes to the code, please add or
   update unit tests as appropriate.
4. If your pull request fails to conform to the PSR standards, include adequate tests, or pass the TravisCI build, we
   may ask you to update your pull request before we accept it. We also reserve the right to deny any pull requests that
   do not align with our standards or goals.
5. If you would like to implement support for a significant feature, please talk to us beforehand to avoid any
   unnecessary or duplicate effort.

## Running the unit tests

The AWS SDK ZF2 Module uses unit tests built for PHPUnit. You can run the unit tests of the SDK after copying
`phpunit.xml.dist` to `phpunit.xml`:

    cp phpunit.xml.dist phpunit.xml

Next, you need to install the dependencies of the module (including the AWS SDK for PHP) using Composer:

    composer.phar install

Now you're ready to run the unit tests using PHPUnit:

    vendor/bin/phpunit

[issues]: https://github.com/aws/aws-sdk-php-zf2-/issues
[pull requests]: https://github.com/aws/aws-sdk-php-zf2/pulls
[license]: http://aws.amazon.com/apache2.0/
[cla]: http://en.wikipedia.org/wiki/Contributor_License_Agreement
[psr-0]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
[psr-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[psr-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[php-fig]: http://php-fig.org
[cs-fixer]: http://cs.sensiolabs.org/
