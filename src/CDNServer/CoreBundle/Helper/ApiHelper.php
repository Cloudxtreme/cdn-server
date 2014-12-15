<?php

namespace CDNServer\CoreBundle\Helper;

/**
 * Class ApiHelper
 * @package CDNServer\CoreBundle\Helper
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class ApiHelper
{
    /**
     * @param array $data
     * @param array $keys
     * @return array
     */
    public static function checkRequiredParameters(array $data, array $keys)
    {
        $error = array();
        foreach ($keys as $k)
        {
            if (!isset($data[$k]))
                $error[] = "Parameter ".$k." is required.";
        }
        if (!empty($error))
            $data['_error'] = $error;
        return $data;
    }


}
