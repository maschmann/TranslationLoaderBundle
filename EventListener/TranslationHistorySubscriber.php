<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\EventListener;

use Asm\TranslationLoaderBundle\Event\TranslationEvent;
use Asm\TranslationLoaderBundle\Model\TranslationHistoryInterface;
use Asm\TranslationLoaderBundle\Model\TranslationHistoryManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class TranslationHistorySubscriber
 *
 * @author marc aschmann <maschmann@gmail.com>
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationHistorySubscriber
{
    /**
     * @var TranslationHistoryManagerInterface
     */
    private $translationHistoryManager;

    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    public function __construct(TranslationHistoryManagerInterface $translationHistoryManager, SecurityContextInterface $securityContext)
    {
        $this->translationHistoryManager = $translationHistoryManager;
        $this->securityContext = $securityContext;
    }

    /**
     * Event-based update of the translation history.
     *
     * @param TranslationEvent $event The event triggering the update
     *
     * @return TranslationHistoryInterface The new translation history entry
     */
    public function updateHistory(TranslationEvent $event)
    {
        $translation = $event->getTranslation();

        $historyEntry = $this->translationHistoryManager->createTranslationHistory();
        $historyEntry->setDateOfChange(new \DateTime());
        $historyEntry->setMessageDomain($translation->getMessageDomain());
        $historyEntry->setTransKey($translation->getTransKey());
        $historyEntry->setTransLocale($translation->getTransLocale());
        $historyEntry->setTranslation($translation->getTranslation());
        $historyEntry->setUserAction(strtolower(substr($event->getName(), 4)));

        if (null !== $token = $this->securityContext->getToken()) {
            $historyEntry->setUserName($token->getUsername());
        } else {
            $historyEntry->setUserName('anonymous');
        }

        $this->translationHistoryManager->updateTranslationHistory($historyEntry);

        return $historyEntry;
    }
}
