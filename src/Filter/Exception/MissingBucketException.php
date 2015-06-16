<?php

namespace AwsModule\Filter\Exception;

use InvalidArgumentException;

/**
 * Exception thrown when no bucket is passed
 */
class MissingBucketException extends InvalidArgumentException
{
}
