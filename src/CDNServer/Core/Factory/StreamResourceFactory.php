<?php

namespace CDNServer\Core\Factory;

use CDNServer\Core\Helper\FileHelper;

/**
 * Class StreamResourceFactory
 * @package CDNServer\Core\Factory
 * @author Pierre LECERF <pierre.lecerf@gmail.com>
 */
class StreamResourceFactory extends ResourceFactory
{
    /**
     * @param $key
     * @param array $options
     * @return Resource
     */
    public function create($key, array $options = array())
    {
        if (!isset($options['name']))
            $options['name'] = $this->generateNameRandomly();
        if (!isset($options['extension']))
            $options['extension'] = $this->getExtension($options);
        return parent::create($key, $options);
    }

    protected function  generateNameRandomly()
    {
        return sha1(time().'resource');
    }

    protected function  getExtension($options)
    {
        if ($extension = FileHelper::getExtensionFromFilename($options['name']))
            return false;
        else if ($extension = FileHelper::getExtensionFromStream($options['data']))
            return $extension;
        else if (isset($options['mimetype']) && ($extension = FileHelper::getExtensionFromMimetype($options['mimetype'])))
            return $extension;
        else
            return false;
    }
}
