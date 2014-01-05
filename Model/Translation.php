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
 * Base implementation of the {@link TranslationInterface} (can be extended by
 * concrete storage layer implementations).
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class Translation implements TranslationInterface
{
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
    protected  $messageDomain;

    /**
     * @var string
     */
    protected  $translation;

    /**
     * @var string
     */
    protected  $dateCreated;

    /**
     * @var string
     */
    protected  $dateUpdated;

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
    public function setDateCreated(\DateTime $dateCreated = null)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * {@inheritDoc}
     */
    public function setDateUpdated(\DateTime $dateUpdated = null)
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }
}
