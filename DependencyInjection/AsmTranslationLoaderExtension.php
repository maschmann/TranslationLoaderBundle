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

        $rolesEnabled = false;
        $rolesCreate  = array();
        $rolesRead    = array();
        $rolesUpdate  = array();
        $rolesDelete  = array();

        // check if we should use roles to check if the user has rights to do stuff
        if (isset($config['roles']['enabled'])
            && true == $config['roles']['enabled']
        ) {
            $rolesEnabled = $config['roles']['enabled'];

            // since the sub nodes are reqired & checked, we can just add the values
            $rolesCreate = $config['roles']['create'];
            $rolesRead   = $config['roles']['read'];
            $rolesUpdate = $config['roles']['update'];
            $rolesDelete = $config['roles']['delete'];

        }

        $container->setParameter('asm_translation_loader.database.entity_manager', $em);
        $container->setParameter('asm_translation_loader.history.enabled', $historyEnabled);
        $container->setParameter('asm_translation_loader.roles.enabled', $rolesEnabled);
        $container->setParameter('asm_translation_loader.roles.create', $rolesCreate);
        $container->setParameter('asm_translation_loader.roles.read', $rolesRead);
        $container->setParameter('asm_translation_loader.roles.update', $rolesUpdate);
        $container->setParameter('asm_translation_loader.roles.delete', $rolesDelete);
    }
}
