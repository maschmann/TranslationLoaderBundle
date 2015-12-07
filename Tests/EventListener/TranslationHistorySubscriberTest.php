<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\EventListener;

use Asm\TranslationLoaderBundle\Event\TranslationEvent;
use Asm\TranslationLoaderBundle\EventListener\TranslationHistorySubscriber;
use Asm\TranslationLoaderBundle\Model\Translation;
use Asm\TranslationLoaderBundle\Model\TranslationHistory;
use Asm\TranslationLoaderBundle\Model\TranslationHistoryManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationHistorySubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TranslationHistoryManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $translationHistoryManager;

    protected function setUp()
    {
        $this->translationHistoryManager = $this->createTranslationHistoryManager();
    }

    /**
     * @dataProvider updateHistoryProvider
     */
    public function testUpdateHistoryProvider($securityContext, $eventName, $expectedUsername, $expectedAction)
    {
        $dateBefore = new \DateTime();
        $translation = $this->createRandomDummyTranslation();
        $subscriber = new TranslationHistorySubscriber($this->translationHistoryManager, $securityContext);
        $this
            ->translationHistoryManager
            ->expects($this->once())
            ->method('updateTranslationHistory')
            ->with($this->isInstanceOf('Asm\TranslationLoaderBundle\Model\TranslationHistoryInterface'), false);
        $event = new TranslationEvent($translation);

        if (method_exists($eventName, 'setName')) {
            $event->setName($eventName);
        }

        $translationHistory = $subscriber->updateHistory($event, $eventName);

        $this->assertGreaterThanOrEqual($dateBefore, $translationHistory->getDateOfChange());
        $this->assertEquals($translation->getMessageDomain(), $translationHistory->getMessageDomain());
        $this->assertEquals($translation->getTransKey(), $translationHistory->getTransKey());
        $this->assertEquals($translation->getTransLocale(), $translationHistory->getTransLocale());
        $this->assertEquals($translation->getTranslation(), $translationHistory->getTranslation());
        $this->assertEquals($expectedUsername, $translationHistory->getUserName());
        $this->assertEquals($expectedAction, $translationHistory->getUserAction());
    }

    public function updateHistoryProvider()
    {
        return array(
            'persist-anonymous-user' => array(
                $this->createAnonymousSecurityContext(),
                TranslationEvent::POST_PERSIST,
                'anonymous',
                'persist',
            ),
            'update-anonymous-user' => array(
                $this->createAnonymousSecurityContext(),
                TranslationEvent::POST_UPDATE,
                'anonymous',
                'update',
            ),
            'remove-anonymous-user' => array(
                $this->createAnonymousSecurityContext(),
                TranslationEvent::POST_REMOVE,
                'anonymous',
                'remove',
            ),
            'persist-authenticated-user' => array(
                $this->createAuthenticatedSecurityContext(),
                TranslationEvent::POST_PERSIST,
                'the username',
                'persist',
            ),
            'update-authenticated-user' => array(
                $this->createAuthenticatedSecurityContext(),
                TranslationEvent::POST_UPDATE,
                'the username',
                'update',
            ),
            'remove-authenticated-user' => array(
                $this->createAuthenticatedSecurityContext(),
                TranslationEvent::POST_REMOVE,
                'the username',
                'remove',
            ),
        );
    }

    /**
     * @return TranslationHistoryManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTranslationHistoryManager()
    {
        $manager = $this->getMock('Asm\TranslationLoaderBundle\Model\TranslationHistoryManagerInterface');
        $manager
            ->expects($this->any())
            ->method('createTranslationHistory')
            ->will($this->returnValue(new DummyTranslationHistory()));

        return $manager;
    }

    /**
     * @return SecurityContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createAnonymousSecurityContext()
    {
        return $this->mockTokenStorage();
    }

    /**
     * @return SecurityContextInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createAuthenticatedSecurityContext()
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token
            ->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('the username'));
        $tokenStorage = $this->mockTokenStorage();
        $tokenStorage
            ->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));

        return $tokenStorage;
    }

    private function createRandomDummyTranslation()
    {
        $translation = new DummyTranslation();
        $translation->setTransKey(md5(uniqid()));
        $translation->setTransLocale(md5(uniqid()));
        $translation->setMessageDomain(md5(uniqid()));
        $translation->setTranslation(md5(uniqid()));

        return $translation;
    }

    private function mockTokenStorage()
    {
        if (interface_exists('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')) {
            return $this->getMock('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface');
        } else {
            return $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        }
    }
}

class DummyTranslation extends Translation
{
}

class DummyTranslationHistory extends TranslationHistory
{
}
