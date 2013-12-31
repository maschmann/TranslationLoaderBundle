<?php
/**
 * @namespace
 */
namespace Asm\TranslationLoaderBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Asm\TranslationLoaderBundle\Entity\Translation;
use Asm\TranslationLoaderBundle\Entity\TranslationHistory;

/**
 * Class TranslationHistorySubscriber
 *
 * @package Asm\TranslationLoaderBundle\EventListener
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Doctrine\Common\EventSubscriber
 * @uses Doctrine\ORM\Event\LifecycleEventArgs
 * @uses Asm\TranslationLoaderBundle\Entity\Translation
 * @uses Asm\TranslationLoaderBundle\Entity\TranslationHistory
 */
class TranslationHistorySubscriber implements EventSubscriber {


    /**
     * @var boolean
     */
    private $isEnabled = false;

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postRemove',
        );
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->index($args, 'update');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->index($args, 'persist');
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $this->index($args, 'remove');
    }

    /**
     * @param LifecycleEventArgs $args
     * @param string $type type of event
     */
    public function index(LifecycleEventArgs $args, $type)
    {
        if ($this->isEnabled) {
            $entity        = $args->getEntity();
            $entityManager = $args->getEntityManager();

            /** @var \Asm\TranslationLoaderBundle\ $entity */
            if ($entity instanceof Translation) {
                /** @var \Asm\TranslationLoaderBundle\Entity\TranslationHistory $historyEvent */
                $historyEvent = new TranslationHistory();
                $historyEvent->setTransKey($entity->getTransKey());
                $historyEvent->setTransLocale($entity->getTransLocale());
                $historyEvent->setMessageDomain($entity->getMessageDomain());
                $historyEvent->setTranslation($entity->getTranslation());
                $historyEvent->setUserAction($type);
                $historyEvent->setUserId(0);
            }
        }
    }


    /**
     * enable/disable subscriber
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->isEnabled = $enabled;
    }


    public function setUser($user)
    {
        //$this->user = $user;
    }
}
