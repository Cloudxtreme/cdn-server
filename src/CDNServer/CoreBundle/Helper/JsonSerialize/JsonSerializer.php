<?php

namespace CDNServer\CoreBundle\Helper\JsonSerialize;

use CDNServer\CoreBundle\Helper\JsonSerialize\JsonSerializable;

/**
 * Since we apparently can't afford a PHP5.4+ version on our servers, here's an approximate
 * (and hacky) implementation of what the \JsonSerializable interface normally allows.
 *
 * @author Pierre LECERF
 */
class JsonSerializer
{
	/**
	 * 
	 * @param mixed 	$var
	 * @param int 		$options = null
	 * @return string
	 */
	public static function json_serialize($var, $options = null)
	{
		return json_encode(self::json_format($var), $options);
	}
	
	public static function json_serialize_reflection($var, $method, $options = null)
	{
		return json_encode(self::json_format_reflection($var, $method), $options);
	}
	
	/**
	 * As one can see, this method only works for a continuous nesting of JsonSerializable
	 * objects. If a JsonSerializable object is to be found within a non-JsonSerializable,
	 * json_format won't recursively format it, and thus, the object will be serialized the
	 * classical way.
	 * 
	 * @param mixed 	$var
	 * @return array
	 */
	public static function json_format($var)
	{
		if (is_array($var))
		{
			foreach ($var as &$v)
				$v = self::json_format($v);
		}
		else if ($var instanceof JsonSerializable)
		{
			$var = $var->jsonSerialize();
			foreach ($var as &$v)
				$v = self::json_format($v);
		}
		return $var;
	}
	/**
	 * In a comparable way to JsonSerializer::json_format, this method will try to use the
	 * specified $method only on a continuous nesting of compliant objects. If the method
	 * $method isn't to be found at a given level, all subsequent nesting will be formatted
	 * using the standard JsonSerializer::json_format method.
	 * 
	 * @param mixed $var
	 * @param string $method
	 * @return array
	 */
	public static function json_format_reflection($var, $method)
	{
		if (is_array($var))
		{
			foreach ($var as &$v)
				$v = self::json_format_reflection($v, $method);
		}
		else if (is_object($var))
		{
			try {
				$invoker = new \ReflectionMethod(get_class($var), $method);
				$var = $invoker->invoke($var);
				foreach ($var as &$v)
					$v = self::json_format_reflection($v, $method);
			}
			catch (\ReflectionException $e) {
				$var = self::json_format($var);
			}
		}
		return $var;
	}
}
