<?php

namespace CDNServer\Core\Fetch;

use CDNServer\Core\Entity\Resource;

/**
 * Interface ResourceFetcherInterface
 * @package CDNServer\Core\Fetch
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
interface ResourceFetcherInterface
{
    /**
     * @param Resource $resource
     * @param $url
     * @return string
     */
    public function fetch(Resource $resource, $url);
}
