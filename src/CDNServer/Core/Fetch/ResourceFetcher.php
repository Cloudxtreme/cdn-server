<?php

namespace CDNServer\Core\Fetch;

use CDNServer\Core\Entity\Resource;
use CDNServer\Core\Fetch\Exception\ResourceFetcherException;
use CDNServer\Core\Write\ResourceWriterInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ResourceFetcher
 * @package CDNServer\Core\Fetch
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceFetcher implements ResourceFetcherInterface
{
    protected $writer;
    protected $em;

    public function __construct(ResourceWriterInterface $writer, EntityManagerInterface $em)
    {
        $this->writer = $writer;
        $this->em = $em;
    }

    public function fetch(Resource $resource, $url)
    {
        if (($data = file_get_contents($url)) === false)
        {
            $this->markForRefresh($resource);
            throw new ResourceFetcherException("The remote file ".$url." could not be retrieved (flagging the target Resource for a refresh).", 1);
        }
        $this->markForRefresh($resource, false);
        return $this->writer->write($resource, $data);
    }

    protected function markForRefresh(Resource $resource, $status = true)
    {
        $resource->setRefresh($status);
        $this->em->persist($resource);
        $this->em->flush($resource);
    }
}
