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
     * @return $this
     */
    public function setTransKey($transKey);

    /**
     * @return string
     */
    public function getTransKey();

    /**
     * @param string $transLocale
     * @return $this
     */
    public function setTransLocale($transLocale);

    /**
     * @return string
     */
    public function getTransLocale();

    /**
     * @param string $messageDomain
     * @return $this
     */
    public function setMessageDomain($messageDomain);

    /**
     * @return string
     */
    public function getMessageDomain();

    /**
     * @param string $translation
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setDateUpdated(\DateTime $dateUpdated);

    /**
     * @return \DateTime
     */
    public function getDateUpdated();

    /**
     * Return an array of translation data.
     *
     * @return array
     */
    public function toArray();
}
