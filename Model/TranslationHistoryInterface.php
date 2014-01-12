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
 * Interface definition for {@link TranslationInterface translation} history
 * entries.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface TranslationHistoryInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * Returns the date when the corresponding update has been executed.
     *
     * @return \DateTime
     */
    public function getDateOfChange();

    /**
     * Sets the date of the change of the translation.
     *
     * This method isn't meant to be called directly but is called automatically
     * by the {@link TranslationHistoryManagerInterface TranslationHistoryManager}
     * layer to ensure data consistency.
     *
     * @param \DateTime $dateOfChange
     */
    public function setDateOfChange(\DateTime $dateOfChange = null);

    /**
     * @param string $messageDomain
     */
    public function setMessageDomain($messageDomain);

    /**
     * @return string
     */
    public function getMessageDomain();

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
     * @param string $translation
     */
    public function setTranslation($translation);

    /**
     * @return string
     */
    public function getTranslation();

    /**
     * @param mixed $userAction
     */
    public function setUserAction($userAction);

    /**
     * @return mixed
     */
    public function getUserAction();

    /**
     * @param string $userName
     */
    public function setUserName($userName = 'anonymous');

    /**
     * @return int
     */
    public function getUserName();
}
