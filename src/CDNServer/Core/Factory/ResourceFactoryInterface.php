<?php

namespace CDNServer\Core\Factory;

/**
 * Interface ResourceFactoryInterface
 * @package CDNServer\Core\Factory
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
interface ResourceFactoryInterface
{
    /**
     * @param $key
     * @param array $options
     * @return Resource
     */
    public function create($key, array $options = array());

    /**
     * @param $key
     * @param array $options
     * @return Resource
     */
    public function check($key, array $options = array());
}
