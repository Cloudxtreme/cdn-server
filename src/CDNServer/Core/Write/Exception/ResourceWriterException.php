<?php

namespace CDNServer\Core\Write\Exception;

/**
 * Class ResourceWriterException
 * @package CDNServer\Core\Write\Exception
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceWriterException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
