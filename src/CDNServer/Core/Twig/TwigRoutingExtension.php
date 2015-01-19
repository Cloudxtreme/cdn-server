<?php

namespace CDNServer\Core\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TwigRoutingExtension extends \Twig_Extension
{
	private $generator;
	
	public function __construct(UrlGeneratorInterface $generator)
	{
		$this->generator = $generator;
	}
	
	public function getFunctions()
	{
		return array(
				'url'		=> new \Twig_Function_Method($this, 'getUrl')
		);
	}
	
	public function getUrl($name, $parameters = array(), $removePort = true)
	{
		$url = $this->generator->generate($name, $parameters, true);
		if ($removePort)
		{
			$components	= parse_url($url);
			if (isset($components['port']))
				$url	=	str_replace(':'.$components['port'], '', $url);
		}
		return $url;
	}
	
	public function getName()
	{
		return 'twig_routing_extension';
	}
}
