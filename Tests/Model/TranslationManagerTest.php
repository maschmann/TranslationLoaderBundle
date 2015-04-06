<?php
/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\Model;

use Asm\TranslationLoaderBundle\Event\TranslationEvent;
use Asm\TranslationLoaderBundle\Model\TranslationInterface;
use Asm\TranslationLoaderBundle\Model\TranslationManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class TranslationManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TranslationManagerInterface
     */
    protected $translationManager;

    /**
     * @var EventDispatcherInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventDispatcher;

    protected function setUp()
    {
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->translationManager = $this->createTranslationManager();
    }

    public function testPostPersistEventIsDispatched()
    {
        $translation = $this->createFreshTranslation();
        $expectedEvent = new TranslationEvent($translation);
        $this
            ->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(TranslationEvent::POST_PERSIST, $this->equalTo($expectedEvent));
        $this->translationManager->updateTranslation($translation);
    }

    public function testPostUpdateEventIsDispatched()
    {
        $translation = $this->createNonFreshTranslation();
        $expectedEvent = new TranslationEvent($translation);
        $this
            ->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(TranslationEvent::POST_UPDATE, $this->equalTo($expectedEvent));
        $this->translationManager->updateTranslation($translation);
    }

    public function testPostRemoveEventIsDispatched()
    {
        $translation = $this->createNonFreshTranslation();
        $expectedEvent = new TranslationEvent($translation);
        $this
            ->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(TranslationEvent::POST_REMOVE, $this->equalTo($expectedEvent));
        $this->translationManager->removeTranslation($translation);
    }

    /**
     * @return TranslationManagerInterface
     */
    abstract protected function createTranslationManager();

    /**
     * @return TranslationInterface
     */
    abstract protected function createFreshTranslation();

    /**
     * @return TranslationInterface
     */
    abstract protected function createNonFreshTranslation();
}
