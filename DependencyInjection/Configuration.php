<?php
/**
 * @namespace Asm\TranslationLoaderBundle\DependencyInjection
 */
namespace Asm\TranslationLoaderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * @package Asm\TranslationLoaderBundle\DependencyInjection
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Component\Config\Definition\Builder\TreeBuilder
 * @uses Symfony\Component\Config\Definition\ConfigurationInterface
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        /** @var \Symfony\Component\Config\Definition\Builder\TreeBuilder $treeBuilder */
        $treeBuilder = new TreeBuilder();

        return $treeBuilder->root('translation_loader')
        ->children()
            ->arrayNode('database')
                ->children()
                    ->scalarNode('entity_manager')->defaultNull()->end()
                ->end()
            ->end()
        ->end();
    }
}
