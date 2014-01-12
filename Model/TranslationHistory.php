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
 * Base implementation of the {@link TranslationHistoryInterface} (can be extended
 * by concrete storage layer implementations).
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class TranslationHistory implements TranslationHistoryInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $transKey;

    /**
     * @var string
     */
    protected $transLocale;

    /**
     * @var string
     */
    protected $messageDomain;

    /**
     * @var string
     */
    protected $userName;

    /**
     * @var string
     */
    protected $userAction;

    /**
     * @var string
     */
    protected $translation;

    /**
     * @var string
     */
    protected $dateOfChange;

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function setDateOfChange(\DateTime $dateOfChange = null)
    {
        if (empty($dateOfChange)) {
            $dateOfChange = new \DateTime();
        }
        $this->dateOfChange = $dateOfChange;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateOfChange()
    {
        return $this->dateOfChange;
    }

    /**
     * {@inheritDoc}
     */
    public function setMessageDomain($messageDomain)
    {
        $this->messageDomain = $messageDomain;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessageDomain()
    {
        return $this->messageDomain;
    }

    /**
     * {@inheritDoc}
     */
    public function setTransKey($transKey)
    {
        $this->transKey = $transKey;
    }

    /**
     * {@inheritDoc}
     */
    public function getTransKey()
    {
        return $this->transKey;
    }

    /**
     * {@inheritDoc}
     */
    public function setTransLocale($transLocale)
    {
        $this->transLocale = $transLocale;
    }

    /**
     * {@inheritDoc}
     */
    public function getTransLocale()
    {
        return $this->transLocale;
    }

    /**
     * {@inheritDoc}
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;
    }

    /**
     * {@inheritDoc}
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * {@inheritDoc}
     */
    public function setUserAction($userAction)
    {
        $this->userAction = $userAction;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserAction()
    {
        return $this->userAction;
    }

    /**
     * {@inheritDoc}
     */
    public function setUserName($userName = 'anonymous')
    {
        $this->userName = $userName;
    }

    /**
     * {@inheritDoc}
     */
    public function getUserName()
    {
        return $this->userName;
    }
}
