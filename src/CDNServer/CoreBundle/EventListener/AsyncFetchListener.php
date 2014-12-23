<?php
/**
 * Created by PhpStorm.
 * User: Eilinel
 * Date: 09/12/14
 * Time: 01:22
 */

namespace CDNServer\CoreBundle\EventListener;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class AsyncFetchListener
{
    protected $container;
    protected $em;
    protected $logger;

    public function __construct(ContainerInterface $container, EntityManager $em, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->em = $em;
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

            if (($data = file_get_contents($attr['url'])) === false)
            {
                $this->logger->error("AsyncFetchListener Error : The file ".$attr['url']." could not be retrieved (flagging the resource for a refresh).");
                $resource = $this->em->getRepository('CDNServerCoreBundle:Resource')->findOneById($attr['resource']->getId());
                $resource->setRefresh(true);
                $this->em->persist($resource);
                $this->em->flush();
                return;
            }

            if (file_put_contents($this->container->getParameter('resource_root_dir').$attr['path'], $data) === false)
            {
                $this->logger->error("AsyncFetchListener Error : An unexpected error occurred and the file could not be written.");
                return;
            }

        }
    }

} 