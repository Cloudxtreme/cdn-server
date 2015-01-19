<?php

namespace CDNServer\Core\Factory;

use CDNServer\Core\Helper\FileHelper;

/**
 * Class UrlResourceFactory
 * @package CDNServer\Core\Factory
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class UrlResourceFactory extends ResourceFactory
{
    /**
     * @param $key
     * @param array $options
     * @throws \Exception
     * @return Resource
     */
    public function create($key, array $options = array())
    {
        if (!isset($options['name']))
            $options['name'] = $this->generateNameFromURL($options['url']);
        if (!isset($options['extension']))
            $options['extension'] = $this->getExtension($options);
        return parent::create($key, $options);
    }

    protected function  generateNameFromURL($url)
    {
        return sha1($url);
    }

    protected function  getExtension($options)
    {
        if ($extension = FileHelper::getExtensionFromFilename($options['name']))
            return $extension;
        else if ($extension = FileHelper::getExtensionFromFilename($options['url']))
            return $extension;
        else
            return false;
    }
}
