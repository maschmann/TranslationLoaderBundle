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
     * @var \Symfony\Component\Security\Core\SecurityContext $seccurityContext
     */
    private $securityContext;


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

                $token    = $this->securityContext->getToken();
                $userName = 'anonymous';
                if (!empty($token)) {
                    $userName = $token->getUsername();
                }

                /** @var \Asm\TranslationLoaderBundle\Entity\TranslationHistory $historyEvent */
                $historyEvent = new TranslationHistory();
                $historyEvent->setTransKey($entity->getTransKey());
                $historyEvent->setTransLocale($entity->getTransLocale());
                $historyEvent->setMessageDomain($entity->getMessageDomain());
                $historyEvent->setTranslation($entity->getTranslation());
                $historyEvent->setUserAction($type);
                $historyEvent->setUserName($userName);
                $historyEvent->setDateOfChange(new \DateTime());

                $entityManager->persist($historyEvent);
                $entityManager->flush();
                $entityManager->detach($historyEvent);
                $entityManager->clear();
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


    /**
     * @param $securityContext
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
    }
}
