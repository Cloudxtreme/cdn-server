<?php

namespace CDNServer\Core\EventListener\Base;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

interface ResponseListenerInterface
{
	public function onKernelResponse(FilterResponseEvent $event);
}
