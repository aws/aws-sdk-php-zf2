<?php
/**
 * Copyright (C) Maestrooo SAS - All Rights Reserved
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Written by Michaël Gallego <mic.gallego@gmail.com>
 */

namespace Aws\Filter\Exception;

use Aws\Common\Exception\AwsExceptionInterface;
use InvalidArgumentException;

/**
 * Exception thrown when no bucket is passed
 */
class MissingBucketException extends InvalidArgumentException implements AwsExceptionInterface
{
}
