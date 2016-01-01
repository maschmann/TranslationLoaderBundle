<?php
/**
 * @namespace Asm\TranslationLoaderBundle\DependencyInjection
 */
namespace Asm\TranslationLoaderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
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

        // association between file extensions and translation loader service ids
        $container->setParameter('asm_translation_loader.translation_loaders', $config['loaders']);
        $container->setParameter('asm_translation_loader.database.entity_manager', $em);

        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        // load the event dispatcher
        $loader->load('event_dispatcher.xml');

        // load the translation manager resource
        $container->setParameter('asm_translation_loader.resources', $config['resources']);
        $loader->load('translation_manager_resource.xml');

        // check if history feature is enabled
        if (isset($config['history']['enabled']) && true === $config['history']['enabled']) {
            $loader->load('translation_history_subscriber.xml');

            $definition = $container->getDefinition('asm_translation_loader.history.subscriber');

            if (interface_exists('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')) {
                $definition->replaceArgument(1, new Reference('security.token_storage'));
            } else {
                $definition->replaceArgument(1, new Reference('security.context'));
            }
        }


        if ('orm' == $config['driver']) {
            $loader->load('orm.xml');
        }

        // load the loader resolver service
        $loader->load('file_loader_resolver.xml');
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
