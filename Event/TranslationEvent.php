<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Event;

use Asm\TranslationLoaderBundle\Model\TranslationInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event that is triggered through a {@link TranslationInterface translation's}
 * lifecycle.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationEvent extends Event
{
    const POST_PERSIST = 'postPersist';
    const POST_UPDATE = 'postUpdate';
    const POST_REMOVE = 'postRemove';

    /**
     * @var TranslationInterface
     */
    private $translation;

    public function __construct(TranslationInterface $translation)
    {
        $this->translation = $translation;
    }

    /**
     * @return TranslationInterface
     */
    public function getTranslation()
    {
        return $this->translation;
    }
}
