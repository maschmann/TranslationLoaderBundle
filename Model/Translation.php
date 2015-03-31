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
    protected $messageDomain;

    /**
     * @var string
     */
    protected $translation;

    /**
     * @var \DateTime
     */
    protected $dateCreated;

    /**
     * @var \DateTime
     */
    protected $dateUpdated;

    /**
     * {@inheritDoc}
     */
    public function setTransKey($transKey)
    {
        $this->transKey = $transKey;

        return $this;
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

        return $this;
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

        return $this;
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

        return $this;
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

        return $this;
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

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return array(
            'transKey' => $this->getTransKey(),
            'transLocale' => $this->getTransLocale(),
            'messageDomain' => $this->getMessageDomain(),
            'translation' => $this->getTranslation(),
            'dateCreated' => $this->getDateCreated()->getTimestamp(),
            'dateUpdated' => $this->getDateUpdated()->getTimestamp(),
        );
    }
}
