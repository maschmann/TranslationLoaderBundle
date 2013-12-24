<?php
/**
 * @namespace Asm\TranslationLoaderBundle\DependencyInjection
 */
namespace Asm\TranslationLoaderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

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

        $container->setParameter('translation_loader.database.entity_manager', $config['database']['entity_manager']);
    }
}
