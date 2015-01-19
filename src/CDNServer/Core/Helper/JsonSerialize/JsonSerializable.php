<?php

namespace CDNServer\Core\Helper\JsonSerialize;

interface JsonSerializable
{
	/**
	 * @return array
	 */
	public function jsonSerialize();
}
