<?php

namespace CDNServer\Core\Write;

use CDNServer\Core\Entity\Resource;

/**
 * Interface ResourceWriterInterface
 * @package CDNServer\Core\Write
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
interface ResourceWriterInterface
{
    /**
     * @param Resource $resource
     * @param $data
     * @return string
     */
    public function write(Resource $resource, $data);

    /**
     * @param Resource $resource
     * @return void
     */
    public function remove(Resource $resource);

    /**
     * @param Resource $resource
     * @return string
     */
    public function generateRemotePath(Resource $resource);

    /**
     * @param string $path
     * @return string
     */
    public function generateLocalPathFromString($path);

    /**
     * @param Resource $resource
     * @return string
     */
    public function generateLocalPath(Resource $resource);

    /**
     * @param string $path
     * @return string
     */
    public function generateRemotePathFromString($path);
}
