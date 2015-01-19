<?php

namespace CDNServer\Core\Session;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CoreSession extends Session
{
	public function __construct(SessionStorageInterface $storage, AttributeBagInterface $attributes, FlashBagInterface $flashes)
	{
		parent::__construct($storage, $attributes, $flashes);
	}
}
