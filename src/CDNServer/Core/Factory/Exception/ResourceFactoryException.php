<?php

namespace CDNServer\Core\Factory\Exception;

/**
 * Class ResourceFactoryException
 * @package CDNServer\Core\Factory\Exception
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceFactoryException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
