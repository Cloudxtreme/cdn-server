<?php

namespace CDNServer\Core\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CDNServerCoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        //TODO use the configuration file instead
        if (file_exists(__DIR__.'/../Resources/config/services_local.yml'))
        	$loader->load('services_local.yml');
        else if (file_exists(__DIR__.'/../../../../app/config/dev_application'))
        	$loader->load('services_dev.yml');
        else if (file_exists(__DIR__.'/../../../../app/config/rec_application'))
        	$loader->load('services_rec.yml');
        else if (file_exists(__DIR__.'/../../../../app/config/prod_application'))
        	$loader->load('services_prod.yml');
        else
        	$loader->load('services.yml');
    }
}
