<?php

namespace CDNServer\Core\EventListener\Exception;

use CDNServer\Core\EventListener\Base\ExceptionListenerInterface;
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
