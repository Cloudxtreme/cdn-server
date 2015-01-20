<?php

namespace CDNServer\Core\Helper;

/**
 * Class ApiHelper
 * @package CDNServer\Core\Helper
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ApiHelper
{
    /**
     * @param array $data
     * @param array $keys
     * @throws \Exception
     * @return array
     */
    public static function checkRequiredParameters(array $data, array $keys)
    {
        $error = array();
        foreach ($keys as $k)
        {
            if (!isset($data[$k]))
                $error[] = "Parameter '".$k."' is required.";
        }
        if (!empty($error))
            throw new \Exception(implode("\n", $error));
        return $data;
    }

    /**
     * @param array $params
     * @param array $keys
     * @return bool
     */
    public static function  hasParameters(array $params, array $keys)
    {
        foreach ($keys as $k)
        {
            if (!array_key_exists($k, $params))
                return false;
        }
        return true;
    }

    /**
     * @param array $params
     * @param $key
     * @param $value
     * @return bool
     */
    public static function  assertParameter(array $params, $key, $value)
    {
        if (isset($params[$key]) && $params[$key] === $value)
            return true;
        return false;
    }
}
