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
        $rootNode = $treeBuilder->root('asm_translation_loader');

        $supportedDrivers = array('orm');

        $rootNode
            ->fixXmlConfig('resource')
            ->fixXmlConfig('loader')
            ->children()
                ->arrayNode('resources')
                    ->useAttributeAsKey('locale')
                    ->prototype('array')
                        ->treatNullLike(array(null))
                        ->beforeNormalization()
                            ->ifTrue(
                                function ($v) {
                                    return is_array($v) && count($v) == 0;
                                }
                            )
                            ->then(
                                function () {
                                    return array(null);
                                }
                            )
                        ->end()
                        ->beforeNormalization()
                            ->ifTrue(
                                function ($v) {
                                    return is_array($v) && isset($v['domain']);
                                }
                            )
                            ->then(
                                function ($v) {
                                    return $v['domain'];
                                }
                            )
                        ->end()
                        ->beforeNormalization()
                            ->ifString()
                                ->then(
                                    function ($v) {
                                        return array($v);
                                    }
                                )
                        ->end()
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
                ->scalarNode('driver')
                    ->validate()
                        ->ifNotInArray($supportedDrivers)
                        ->thenInvalid(
                            'The driver %s is not supported. Please choose one of '.json_encode($supportedDrivers)
                        )
                    ->end()
                    ->defaultValue('orm')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('loaders')
                    ->beforeNormalization()
                        ->ifNull()
                            ->then(
                                function () {
                                    return array();
                                }
                            )
                    ->end()
                    ->beforeNormalization()
                        ->always(function ($values) {
                            foreach ($values as $key => $value) {
                                if (is_array($value)) {
                                    $values[$value['extension']] = $value['value'];
                                    unset($values[$key]);
                                }
                            }

                            return array_merge(array(
                                'xlf' => 'translation.loader.xliff',
                                'yaml' => 'translation.loader.yml',
                            ), $values);
                        })
                    ->end()
                    ->useAttributeAsKey('extension')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('database')
                    ->children()
                        ->scalarNode('entity_manager')
                            ->defaultValue('default')
                            ->info('Optional entity manager for separate translations handling.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('history')
                    ->canBeEnabled()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultFalse()
                            ->info(
                                'Enables historytracking for translation changes. Uses user id from registered users as reference'
                            )
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('translation_manager')
                    ->defaultValue('Asm\TranslationLoaderBundle\Doctrine\TranslationManager')
                ->end()
                ->scalarNode('translation_history_manager')
                    ->defaultValue('Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager')
                ->end()
                ->scalarNode('translation_class')
                    ->defaultValue('Asm\TranslationLoaderBundle\Entity\Translation')
                ->end()
                ->scalarNode('translation_history_class')
                    ->defaultValue('Asm\TranslationLoaderBundle\Entity\TranslationHistory')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
