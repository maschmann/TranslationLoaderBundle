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
 * Base {@link TranslationManagerInterface} implementation (can be extended by
 * concrete implementations).
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class TranslationManager implements TranslationManagerInterface
{
    /**
     * Class implementing the {@link TranslationInterface} managed by this manager
     * @var string
     */
    protected $class;

    /**
     * @param string $class Class name of managed {@link TranslationInterface}
     *                      objects
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function createTranslation()
    {
        /** @var TranslationInterface $translation */
        $translation = new $this->class();
        $translation->setDateCreated(new \DateTime());

        return $translation;
    }

    /**
     * {@inheritDoc}
     */
    public function findAllTranslations()
    {
        return $this->findTranslationsBy(array());
    }

    /**
     * {@inheritDoc}
     */
    public function findTranslationsByLocaleAndDomain($locale, $domain = 'messages')
    {
        return $this->findTranslationsBy(array(
            'transLocale' => $locale,
            'messageDomain' => $domain,
        ));
    }
}
