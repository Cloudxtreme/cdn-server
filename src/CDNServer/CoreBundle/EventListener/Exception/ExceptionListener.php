<?php

namespace CDNServer\CoreBundle\EventListener\Exception;

use CDNServer\CoreBundle\EventListener\Base\ExceptionListenerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener implements ExceptionListenerInterface
{
	private $router;
	
	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		$exception = $event->getException();
	}
}
