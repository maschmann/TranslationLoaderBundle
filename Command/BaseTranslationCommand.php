<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Command;

use Asm\TranslationLoaderBundle\Model\TranslationManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Translation\Writer\TranslationWriter;

/**
 * Base class for all AsmTranslationLoaderBundle commands.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class BaseTranslationCommand extends Command implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Returns the configured translation manager.
     *
     * @return TranslationManagerInterface
     */
    protected function getTranslationManager()
    {
        return $this->container->get('asm_translation_loader.translation_manager');
    }

    /**
     * Returns the translation writer.
     *
     * @return TranslationWriter
     */
    protected function getTranslationWriter()
    {
        return $this->container->get('translation.writer');
    }

    /**
     * Returns the current application kernel.
     *
     * @return KernelInterface
     */
    protected function getKernel()
    {
        return $this->container->get('kernel');
    }

    /**
     * Returns the Filesystem service.
     *
     * @return Filesystem
     */
    protected function getFilesystem()
    {
        return $this->container->get('filesystem');
    }
}
