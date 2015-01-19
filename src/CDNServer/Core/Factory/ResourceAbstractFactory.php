<?php

namespace CDNServer\Core\Factory;

use CDNServer\Core\Helper\ApiHelper;
use CDNServer\Core\Write\ResourceWriterInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ResourceAbstractFactory
 * @package CDNServer\Core\Factory
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ResourceAbstractFactory extends ResourceFactory
{
    protected $urlFactory;
    protected $streamFactory;

    public function __construct(ResourceWriterInterface $writer, EntityManagerInterface $em)
    {
        parent::__construct($writer, $em);
        $this->urlFactory = new UrlResourceFactory($writer, $em);
        $this->streamFactory = new StreamResourceFactory($writer, $em);
    }

    public function create($key, array $options = array())
    {
        if (ApiHelper::hasParameters($options, array('url')))
            return $this->urlFactory->create($key, $options);
        else if (ApiHelper::hasParameters($options, array('data')))
            return $this->streamFactory->create($key, $options);
        return null;
    }

    public function check($key, array $options = array())
    {
        if (ApiHelper::hasParameters($options, array('url')))
            return $this->urlFactory->check($key, $options);
        else if (ApiHelper::hasParameters($options, array('data')))
            return $this->streamFactory->check($key, $options);
        return null;
    }
}
