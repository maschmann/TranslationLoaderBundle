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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param string                   $class           Class name of managed {@link TranslationInterface} objects
     * @param EventDispatcherInterface $eventDispatcher Event dispatcher used to propagate new, modified
     *                                                  and removed translations
     */
    public function __construct($class, EventDispatcherInterface $eventDispatcher)
    {
        $this->class = $class;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function createTranslation()
    {
        /** @var TranslationInterface $translation */
        $translation = new $this->class();
        $translation->setDateCreated(new \DateTime());

        return $translation;
    }

    /**
     * {@inheritdoc}
     */
    public function findAllTranslations()
    {
        return $this->findTranslationsBy(array());
    }

    /**
     * {@inheritdoc}
     */
    public function findTranslationsByLocaleAndDomain($locale, $domain = 'messages')
    {
        return $this->findTranslationsBy(array(
            'transLocale' => $locale,
            'messageDomain' => $domain,
        ));
    }
}
