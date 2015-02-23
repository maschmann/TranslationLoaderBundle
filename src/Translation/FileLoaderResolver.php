<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Translation;

use Symfony\Component\Translation\Loader\LoaderInterface;

/**
 * Translation loader resolver.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class FileLoaderResolver
{
    /**
     * @var LoaderInterface[]
     */
    private $loaders = array();

    /**
     * @param LoaderInterface $loader    The translation loader
     * @param string          $extension The file extension
     */
    public function registerLoader(LoaderInterface $loader, $extension)
    {
        $this->loaders[$extension] = $loader;
    }

    /**
     * Checks whether or not a translation loader for a file extension is registered.
     *
     * @param string $extension The file extension
     *
     * @return boolean True if such a loader is registered, false otherwise
     */
    public function hasLoader($extension)
    {
        return isset($this->loaders[$extension]);
    }

    /**
     * Resolves the translation loader for a file extension.
     *
     * @param string $extension The file extension
     *
     * @return LoaderInterface The resolved loader or null if no loader is
     *                         associated with the given extension
     */
    public function resolveLoader($extension)
    {
        if ($this->hasLoader($extension)) {
            return $this->loaders[$extension];
        }

        return null;
    }
}
