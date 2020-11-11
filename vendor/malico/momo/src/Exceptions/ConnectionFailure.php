<?php

namespace Malico\Momo\Exceptions;

use Exception;

class ConnectionFailure extends Exception
{
    /**
     * Throw connection Expception.
     *
     * @param string $error
     *
     * @return static Exception
     */
    public static function failedConnection($error)
    {
        return new static($error);
    }
}
