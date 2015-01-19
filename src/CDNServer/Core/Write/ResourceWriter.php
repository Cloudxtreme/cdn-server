<?php

namespace CDNServer\Core\Write;

use CDNServer\Core\Entity\Resource;
use CDNServer\Core\Write\Exception\ResourceWriterException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ResourceWriter
 * @package CDNServer\Core\Write
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceWriter implements ResourceWriterInterface
{
    protected $resourceRemoteRoot;
    protected $resourceLocalRoot;
    protected $em;

    public function __construct($remoteRoot, $localRoot, EntityManagerInterface $em)
    {
        $this->resourceRemoteRoot = $remoteRoot;
        $this->resourceLocalRoot = $localRoot;
        $this->em = $em;
    }

    /**
     * @param Resource $resource
     * @param $data
     * @throws ResourceWriterException
     * @return string
     */
    public function write(Resource $resource, $data)
    {
        $target = $this->generateLocalPath($resource);
        if (file_put_contents($target, $data) === false)
        {
            $this->markForRefresh($resource);
            throw new ResourceWriterException("The data retrieved for Resource #".$resource->getId()." could not be written to target ".$target." (flagging the Resource for a refresh).", 1);
        }
        return $this->generateRemotePath($resource);
    }

    /**
     * @param Resource $resource
     * @throws ResourceWriterException
     */
    public function remove(Resource $resource)
    {
        $target = $this->generateLocalPath($resource);
        if (!unlink($target))
            throw new ResourceWriterException("The Resource #".$resource->getId()." could not be deleted at target ".$target.".", 2);
    }

    public function generateLocalPath(Resource $resource)
    {
        return $this->resourceLocalRoot.$resource->getPath();
    }
    public function generateLocalPathFromString($path)
    {
        return $this->resourceLocalRoot.$path;
    }
    public function generateRemotePath(Resource $resource)
    {
        return $this->resourceRemoteRoot.$resource->getPath();
    }
    public function generateRemotePathFromString($path)
    {
        return $this->resourceRemoteRoot.$path;
    }

    protected function markForRefresh(Resource $resource)
    {
        $resource->setRefresh(true);
        $this->em->persist($resource);
        $this->em->flush($resource);
    }
}