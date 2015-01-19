<?php

namespace CDNServer\Core\Fetch\Exception;

/**
 * Class ResourceFetcherException
 * @package CDNServer\Core\Fetch\Exception
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceFetcherException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
