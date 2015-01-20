<?php

namespace CDNServer\Core\Factory;

use CDNServer\Core\Entity\Resource;
use CDNServer\Core\Factory\Exception\ResourceFactoryException;
use CDNServer\Core\Helper\FileHelper;
use CDNServer\Core\Write\ResourceWriterInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AbstractResourceFactory
 * @package CDNServer\Core\Factory
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceFactory implements ResourceFactoryInterface
{
    protected $writer;
    protected $em;

    public function __construct(ResourceWriterInterface $writer, EntityManagerInterface $em)
    {
        $this->writer = $writer;
        $this->em = $em;
    }

    /**
     * @param $key
     * @param array $options
     * @throws ResourceFactoryException
     * @return Resource
     */
    public function create($key, array $options = array())
    {
        $project = $this->getProject($key);

        $name = $options['name'];
        $extension = $options['extension'];
        $filename = $name.($extension !== false ? '.'.$extension : '');

        $resource = $this->em->getRepository('CDNServerCore:Resource')->findOneByProjectAndName($project->getId(), $name);
        if ($resource && !$options['update'])
            throw new ResourceFactoryException("The resource ".$name." already exists, either use another name or set the 'update' option to true.", 1);
        else if (!$resource)
            $resource = new Resource($name, $filename, $project);
        else if ($resource->getFilename() !== $filename)
        {
            $this->writer->remove($resource);
            $resource->setFilename($filename);
        }
        $this->em->persist($resource);
        $this->em->flush($resource);

        return $resource;
    }

    public function check($key, array $options = array())
    {
        $project = $this->getProject($key);

        $name = $options['name'];

        if (!($resource = $this->em->getRepository('CDNServerCore:Resource')->findOneByProjectAndName($project->getId(), $name)))
            throw new ResourceFactoryException("The resource ".$name." could not be found for the ".$project->getName()." project.", 2);

        return $resource;
    }

    public function  getProject($key)
    {
        if (!($project = $this->em->getRepository('CDNServerCore:Project')->findOneBy(array('ukey' => $key))))
            throw new ResourceFactoryException("Supplied key is invalid.");
        return $project;
    }
}