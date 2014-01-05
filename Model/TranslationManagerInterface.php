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
 * Interface definition for classes that manage Translation objects.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface TranslationManagerInterface
{
    /**
     * Creates a new {@link TranslationInterface} instance.
     *
     * @return TranslationInterface The new translation
     */
    public function createTranslation();

    /**
     * Finds a translation based on some criteria.
     *
     * @param array $criteria Criteria to filter the translations by
     *
     * @return TranslationInterface Translation matched by the criteria or null
     *                              if no matching translation has been found
     */
    public function findTranslationBy(array $criteria);

    /**
     * Finds all translations.
     *
     * @return TranslationInterface[] The translations
     */
    public function findAllTranslations();

    /**
     * Finds translations based on some criteria.
     *
     * @param array $criteria
     *
     * @return TranslationInterface[] The translations matched by the criteria
     */
    public function findTranslationsBy(array $criteria);

    /**
     * Finds translations by locale and domain.
     *
     * @param string $locale Locale to filter by
     * @param string $domain Message domain to filter by
     *
     * @return TranslationInterface[] The translations matched by the criteria
     */
    public function findTranslationsByLocaleAndDomain($locale, $domain = 'messages');

    /**
     * Writes a new or modified translation to the underlying storage.
     *
     * @param TranslationInterface $translation The translation to update
     */
    public function updateTranslation(TranslationInterface $translation);

    /**
     * Removes a translation.
     *
     * @param TranslationInterface $translation The translation to remove
     */
    public function removeTranslation(TranslationInterface $translation);
}
