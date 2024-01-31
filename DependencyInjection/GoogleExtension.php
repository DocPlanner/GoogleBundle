<?php

namespace AntiMattr\GoogleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class GoogleExtension extends Extension
{
    /**
     * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration;
        $config        = $this->processConfiguration($configuration, $configs);

        $this->adwordsLoad($config['adwords'], $container);
        $this->analyticsLoad($config['analytics'], $container);
        $this->mapsLoad($config['maps'], $container);
    }

    private function adwordsLoad(array $config, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('adwords.xml');

        $container->setParameter('google.adwords.conversions', $config['conversions']);
    }

    private function analyticsLoad(array $config, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('analytics.xml');

        $container->setParameter('google.analytics.dashboard', $config['dashboard']);
        $container->setParameter('google.analytics.whitelist', $config['whitelist']);
        $container->setParameter('google.analytics.js_source_https', $config['js_source_https']);
        $container->setParameter('google.analytics.js_source_http', $config['js_source_http']);
        $container->setParameter('google.analytics.js_source_endpoint', $config['js_source_endpoint']);
        $container->setParameter('google.analytics.trackers', $config['trackers']);
    }

    private function mapsLoad(array $config, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('maps.xml');

        $container->setParameter('google.maps.config', $config['config']);
    }

    public function getAlias()
    {
        return 'google';
    }
}
