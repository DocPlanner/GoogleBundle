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

        $modules = [
            'analytics' => [],
            'maps'      => [],
        ];

        foreach ($configs as $singleConfig) {
            foreach (array_keys($modules) as $module) {
                if (array_key_exists($module, $singleConfig)) {
                    $modules[$module][] = isset($singleConfig[$module]) ? $singleConfig[$module] : [];
                }
            }
        }

        $this->adwordsLoad($config['adwords'], $container);
        $this->analyticsLoad($modules['analytics'], $container);
        $this->mapsLoad($modules['maps'], $container);
    }

    private function adwordsLoad(array $config, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('adwords.xml');

        $container->setParameter('google.adwords.conversions', $config['conversions']);
    }

    private function analyticsLoad(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('analytics.xml');

        $trackers = [];
        foreach ($configs as $config) {
            $trackers = array_merge($trackers, isset($config['trackers']) ? $config['trackers'] : []);
            if (isset($config['dashboard'])) {
                $container->setParameter('google.analytics.dashboard', $config['dashboard']);
            }
            if (isset($config['whitelist'])) {
                $container->setParameter('google.analytics.whitelist', $config['whitelist']);
            }
            if (isset($config['js_source_https'])) {
                $container->setParameter('google.analytics.js_source_https', $config['js_source_https']);
            }
            if (isset($config['js_source_http'])) {
                $container->setParameter('google.analytics.js_source_http', $config['js_source_http']);
            }
            if (isset($config['js_source_endpoint'])) {
                $container->setParameter('google.analytics.js_source_endpoint', $config['js_source_endpoint']);
            }
        }
        $container->setParameter('google.analytics.trackers', $trackers);
    }

    private function mapsLoad(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('maps.xml');

        foreach ($configs as $config) {
            if (isset($config['config'])) {
                $container->setParameter('google.maps.config', $config['config']);
            }
        }
    }

    public function getAlias()
    {
        return 'google';
    }
}
