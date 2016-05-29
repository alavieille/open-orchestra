<?php

namespace OpenOrchestra\WorkflowFunctionModelBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('open_orchestra_workflow_function_model');

        $rootNode->children()
            ->arrayNode('document')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('workflow_function')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\WorkflowFunctionModelBundle\Document\WorkflowFunction')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\WorkflowFunctionModelBundle\Repository\WorkflowFunctionRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('workflow_right')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\WorkflowFunctionModelBundle\Document\WorkflowRight')->end()
                            ->scalarNode('repository')->defaultValue('OpenOrchestra\WorkflowFunctionModelBundle\Repository\WorkflowRightRepository')->end()
                        ->end()
                    ->end()
                    ->arrayNode('authorization')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\WorkflowFunctionModelBundle\Document\Authorization')->end()
                        ->end()
                    ->end()
                    ->arrayNode('reference')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('class')->defaultValue('OpenOrchestra\WorkflowFunctionModelBundle\Document\Reference')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
