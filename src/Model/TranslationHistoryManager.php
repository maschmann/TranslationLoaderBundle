<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Model;

/**
 * Base {@link TranslationHistoryManagerInterface} implementation (can be extended
 * by concrete implementations).
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class TranslationHistoryManager implements TranslationHistoryManagerInterface
{
    /**
     * Class implementing the {@link TranslationHistoryInterface} managed by this
     * manager
     * @var string
     */
    protected $class;

    /**
     * @param string $class Class name of managed {@link TranslationHistoryInterface}
     *                      objects
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function createTranslationHistory()
    {
        return new $this->class();
    }
}
