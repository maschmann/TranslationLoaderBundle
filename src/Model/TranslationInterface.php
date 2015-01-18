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
 * Interface definition for {@link Translation} entries.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface TranslationInterface
{
    /**
     * @param string $transKey
     */
    public function setTransKey($transKey);

    /**
     * @return string
     */
    public function getTransKey();

    /**
     * @param string $transLocale
     */
    public function setTransLocale($transLocale);

    /**
     * @return string
     */
    public function getTransLocale();

    /**
     * @param string $messageDomain
     */
    public function setMessageDomain($messageDomain);

    /**
     * @return string
     */
    public function getMessageDomain();

    /**
     * @param string $translation
     */
    public function setTranslation($translation);

    /**
     * @return string
     */
    public function getTranslation();

    /**
     * Sets the date of the change of the translation.
     *
     * This method isn't meant to be called directly but is called automatically
     * by the {@link TranslationHistoryManagerInterface TranslationHistoryManager}
     * layer to ensure data consistency.
     *
     * @param \DateTime $dateCreated
     */
    public function setDateCreated(\DateTime $dateCreated);

    /**
     * @return \DateTime
     */
    public function getDateCreated();

    /**
     * Sets the date of the change of the translation.
     *
     * This method isn't meant to be called directly but is called automatically
     * by the {@link TranslationHistoryManagerInterface TranslationHistoryManager}
     * layer to ensure data consistency.
     *
     * @param \DateTime $dateUpdated
     */
    public function setDateUpdated(\DateTime $dateUpdated);

    /**
     * @return \DateTime
     */
    public function getDateUpdated();
}
