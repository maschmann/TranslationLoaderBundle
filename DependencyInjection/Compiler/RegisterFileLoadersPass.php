<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterFileLoadersPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('asm_translation_loader.file_loader_resolver')) {
            return;
        }

        /** @var \Asm\TranslationLoaderBundle\Translation\FileLoaderResolver $fileLoader */
        $fileLoader = $container->get('asm_translation_loader.file_loader_resolver');
        $loaders = $container->findTaggedServiceIds('translation.loader');

        foreach ($loaders as $id => $tagAttributes) {
            $definition = $container->findDefinition($id);

            // skip non file loaders
            if (false === strpos($definition->getClass(), 'FileLoader')) {
                continue;
            }

            foreach ($tagAttributes as $attributes) {
                $fileLoader->registerLoader($attributes['alias'], $container->get($id));
            }
        }

        // additional file loaders can be registered in the configuration
        if ($container->hasParameter('asm_translation_loader.translation_loaders')) {
            $additionalLoaders = $container->getParameter('asm_translation_loader.translation_loaders');

            foreach ($additionalLoaders as $extension => $loaderId) {
                $fileLoader->registerLoader($extension, $container->get($loaderId));
            }
        }
    }
}
