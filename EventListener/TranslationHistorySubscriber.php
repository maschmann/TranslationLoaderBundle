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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
     * @var SecurityContextInterface|TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TranslationHistoryManagerInterface             $translationHistoryManager
     * @param SecurityContextInterface|TokenStorageInterface $tokenStorage
     */
    public function __construct(
        TranslationHistoryManagerInterface $translationHistoryManager,
        $tokenStorage
    ) {
        $this->translationHistoryManager = $translationHistoryManager;

        if (!$tokenStorage instanceof SecurityContextInterface && !$tokenStorage instanceof TokenStorageInterface) {
            throw new \InvalidArgumentException('Token storage must be an instance of Symfony\Component\Security\Core\SecurityContextInterface or Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface.');
        }

        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Event-based update of the translation history.
     *
     * @param TranslationEvent $event     The event triggering the update
     * @param string           $eventName The name of the triggered event
     *
     * @return TranslationHistoryInterface The new translation history entry
     */
    public function updateHistory(TranslationEvent $event, $eventName = null)
    {
        $translation = $event->getTranslation();

        $historyEntry = $this->translationHistoryManager->createTranslationHistory();
        $historyEntry->setDateOfChange(new \DateTime());
        $historyEntry->setMessageDomain($translation->getMessageDomain());
        $historyEntry->setTransKey($translation->getTransKey());
        $historyEntry->setTransLocale($translation->getTransLocale());
        $historyEntry->setTranslation($translation->getTranslation());

        if (null === $eventName) {
            $eventName = $event->getName();
        }

        $historyEntry->setUserAction(strtolower(substr($eventName, 4)));

        if (null !== $token = $this->tokenStorage->getToken()) {
            $historyEntry->setUserName($token->getUsername());
        } else {
            $historyEntry->setUserName('anonymous');
        }

        $this->translationHistoryManager->updateTranslationHistory($historyEntry);

        return $historyEntry;
    }
}
