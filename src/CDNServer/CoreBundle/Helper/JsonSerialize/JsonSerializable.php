<?php

namespace CDNServer\CoreBundle\Helper\JsonSerialize;

interface JsonSerializable
{
	/**
	 * @return array
	 */
	public function jsonSerialize();
}
