<?php
/**
 * @namespace Asm\TranslationLoaderBundle\DependencyInjection
 */
namespace Asm\TranslationLoaderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * @package Asm\TranslationLoaderBundle\DependencyInjection
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Component\DependencyInjection\ContainerBuilder
 * @uses Symfony\Component\Config\FileLocator
 * @uses Symfony\Component\HttpKernel\DependencyInjection\Extension
 * @uses Symfony\Component\DependencyInjection\Loader
 */
class AsmTranslationLoaderExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // set entity manager for translations
        $em = 'default';
        if (isset($config['database']['entity_manager'])
            && $config['database']['entity_manager'] != 'default'
        ) {
            $em = $config['database']['entity_manager'];
        }

        // check if history feature is enabled
        $historyEnabled = false;
        if (isset($config['history']['enabled'])
            && true == $config['history']['enabled']
        ) {
            $historyEnabled = $config['history']['enabled'];
        }

        $container->setParameter('asm_translation_loader.database.entity_manager', $em);
        $container->setParameter('asm_translation_loader.history.enabled', $historyEnabled);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        // load the translation manager resource
        $container->setParameter('asm_translation_loader.resources', $config['resources']);
        $loader->load('translation_manager_resource.xml');

        if ('orm' == $config['driver']) {
            $loader->load('orm.xml');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getNamespace()
    {
        return 'https://github.com/maschmann/TranslationLoaderBundle/schema/dic';
    }

    /**
     * {@inheritDoc}
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/schema';
    }
}
