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
 * Interface definition for classes that manage {@link TranslationHistoryInterface}
 * objects.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface TranslationHistoryManagerInterface
{
    /**
     * Creates a new {@link TranslationHistoryInterface} instance.
     *
     * @return TranslationHistoryInterface The new translation history entry
     */
    public function createTranslationHistory();

    /**
     * Writes a new or modified translation history to the underlying storage.
     *
     * @param TranslationHistoryInterface $translationHistory The translation history entry to update
     * @param bool                        $clear              Whether or not to clear the manager for performance reasons
     *                                                        after the entry has been processed
     */
    public function updateTranslationHistory(TranslationHistoryInterface $translationHistory, $clear = false);
}
