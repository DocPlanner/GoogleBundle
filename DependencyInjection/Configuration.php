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
                        ->variableNode('trackers')
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
                        ->variableNode('config')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
