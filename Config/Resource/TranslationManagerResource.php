<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Config\Resource;

use Asm\TranslationLoaderBundle\Translation\DatabaseLoader;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Resource plugging the TranslationManager into the Symfony Translator.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationManagerResource implements ResourceInterface
{
    /**
     * @var DatabaseLoader
     */
    private $loader;

    public function __construct(DatabaseLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * {@inheritDoc}
     */
    public function isFresh($timestamp)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getResource()
    {
        return $this->loader;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return 'DatabaseLoader';
    }
}
