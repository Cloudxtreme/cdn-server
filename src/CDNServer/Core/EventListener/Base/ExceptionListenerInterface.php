<?php

namespace CDNServer\Core\EventListener\Base;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * 
 * @author Pierre LECERF
 */
interface ExceptionListenerInterface
{
	public function onKernelException(GetResponseForExceptionEvent $event);
}
