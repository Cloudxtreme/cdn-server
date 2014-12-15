<?php

namespace CDNServer\CoreBundle\EventListener\Base;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

interface RequestListenerInterface
{
	public function onKernelRequest(GetResponseEvent $event);
}
