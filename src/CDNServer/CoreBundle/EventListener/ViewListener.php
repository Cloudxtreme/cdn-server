<?php

namespace CDNServer\CoreBundle\EventListener;

use CDNServer\CoreBundle\EventListener\Base\ViewListenerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * Adds Response event listener to render no-Response
 * controller results (arrays).
 * 
 * @author Pierre LECERF
 */
class ViewListener implements ViewListenerInterface
{
	private $watchedNamespace = 'CDNServer\\CoreBundle\\';
	private $watchedPrefix = 'CDNServerCoreBundle:';
	private $templating;
	private $engine;

	/**
	 * Initializes listener.
	 *
	 * @param EngineInterface $templating  Templating engine
	 * @param string          $engine      Default engine name
	 */
	public function __construct(EngineInterface $templating, $engine)
	{
		$this->templating       = $templating;
		$this->engine           = $engine;
	}

	/**
	 * Patches response on empty responses.
	 *
	 * @param GetResponseForControllerResultEvent $event Event instance
	 */
	public function onKernelView(GetResponseForControllerResultEvent $event)
	{
		if (is_array($event->getControllerResult()))
		{
			$request 	= $event->getRequest();

			$attributes = $request->attributes;

			$full_path	= $attributes->get('_controller');

			//HACKY : a not-so-elegant way to fix symfony fragment renderings
			if (strstr($full_path, $this->watchedPrefix) !== false)
			{
				$full_path = str_replace($this->watchedPrefix, $this->watchedNamespace.'Controller\\', $full_path);
				$full_path = str_replace(':', 'Controller::', $full_path);
			}

			$controller	= str_replace($this->watchedNamespace, '', $full_path);

			list($class, $action)	= explode('::', $controller);

			if (!preg_match('/Controller\\\(.+)Controller$/', $class, $matchController))
				throw new \InvalidArgumentException(sprintf('The "%s" class does not look like a controller class (it must be in a "Controller" sub-namespace and the class name must end with "Controller")', get_class($controller[0])));

			$view  		= preg_replace('/Action$/', '', $action);
			$directory	= str_replace('\\', '/', $matchController[1]);

			$event->setResponse($this->templating->renderResponse(
					sprintf('CDNServerCoreBundle:%s:%s.%s.%s', $directory, $view, $request->getRequestFormat(), $this->engine),
					$event->getControllerResult()
			));
		}
	}
}