<?php

namespace CDNServer\Core\EventListener;

use CDNServer\Core\Fetch\Exception\ResourceFetcherException;
use CDNServer\Core\Fetch\ResourceFetcherInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Class AsyncFetchListener
 * @package CDNServer\Core\EventListener
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class AsyncFetchListener
{
    protected $container;
    protected $fetcher;
    protected $logger;

    public function __construct(ContainerInterface $container, ResourceFetcherInterface $fetcher, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->fetcher = $fetcher;
        $this->logger = $logger;
    }

    public function onKernelTerminate(PostResponseEvent $pre)
    {
        //Symfony2 locks the session for the Request's lifecycle.
        //Here, we need this method to be executed asynchronously.
        //Thus, we release the session immediately.
        //$pre->getRequest()->getSession()->save();
        $this->container->get('session')->save();
        session_write_close();

        if ($pre->getRequest()->attributes->has('fetch_request'))
        {
            $attr = $pre->getRequest()->attributes->get('fetch_request');

            try {
                $this->fetcher->fetch($attr['resource'], $attr['url']);
            }
            catch (ResourceFetcherException $e) {
                $this->logger->error("AsyncFetchListener Error : ".$e->getMessage());
            }
        }
    }

} 