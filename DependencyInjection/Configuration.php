<?php

namespace AntiMattr\GoogleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('google');
        $rootNode    = !method_exists($treeBuilder, 'getRootNode') ? $treeBuilder->root('google') : $treeBuilder->getRootNode();

        $this->appendAdwordsSection($rootNode);
        $this->appendAnalyticsSection($rootNode);
        $this->appendMapsSection($rootNode);

        return $treeBuilder;
    }

    private function appendAdwordsSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('adwords')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('conversions')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('id')->defaultNull()->end()
                                    ->scalarNode('label')->defaultNull()->end()
                                    ->scalarNode('value')->defaultNull()->end()
                                    ->scalarNode('format')->defaultNull()->end()
                                    ->scalarNode('color')->defaultNull()->end()
                                    ->scalarNode('language')->defaultNull()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function appendAnalyticsSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('analytics')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('dashboard')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('api_key')->end()
                                ->scalarNode('client_id')->end()
                                ->scalarNode('table_id')->end()
                            ->end()
                        ->end()
                        ->arrayNode('whitelist')
                            ->ignoreExtraKeys(false)
                            ->addDefaultsIfNotSet()
                        ->end()
                        ->scalarNode('js_source_https')->defaultValue('https://')->end()
                        ->scalarNode('js_source_http')->defaultValue('http://')->end()
                        ->scalarNode('js_source_endpoint')->defaultValue('stats.g.doubleclick.net/dc.js')->end()
                        ->arrayNode('trackers')
                            ->ignoreExtraKeys(false)
                            ->addDefaultsIfNotSet()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function appendMapsSection(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->children()
                ->arrayNode('maps')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('config')
                            ->ignoreExtraKeys(false)
                            ->addDefaultsIfNotSet()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
