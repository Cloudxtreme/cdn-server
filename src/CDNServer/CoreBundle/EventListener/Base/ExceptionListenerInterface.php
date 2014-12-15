<?php

namespace CDNServer\CoreBundle\EventListener\Base;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * 
 * @author Pierre LECERF
 */
interface ExceptionListenerInterface
{
	public function onKernelException(GetResponseForExceptionEvent $event);
}
