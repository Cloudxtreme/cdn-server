<?php

namespace CDNServer\Core\EventListener\Base;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

/**
 * 
 * @author Pierre LECERF
 */
interface ViewListenerInterface
{
	public function onKernelView(GetResponseForControllerResultEvent $event);
}
