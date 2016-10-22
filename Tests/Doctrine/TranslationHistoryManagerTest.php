<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\Doctrine;

use Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager;
use Asm\TranslationLoaderBundle\Entity\TranslationHistory;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationHistoryManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManager;

    /**
     * @var TranslationHistoryManager
     */
    private $translationManager;

    protected function setUp()
    {
        $this->createObjectManager();
        $this->translationManager = new TranslationHistoryManager(
            $this->objectManager,
            'Asm\TranslationLoaderBundle\Entity\TranslationHistory'
        );
    }

    public function testCreateTranslationHistory()
    {
        $translation = $this->translationManager->createTranslationHistory();
        $this->assertInstanceOf('Asm\TranslationLoaderBundle\Entity\TranslationHistory', $translation);
    }

    public function testUpdateTranslationHistory()
    {
        $translation = $this->createTranslationHistory();
        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($translation));
        $this->objectManager
            ->expects($this->once())
            ->method('flush');
        $this
            ->objectManager
            ->expects($this->never())
            ->method('clear');

        $this->translationManager->updateTranslationHistory($translation);
    }

    public function testUpdateTranslationHistoryAndClear()
    {
        $translation = $this->createTranslationHistory();
        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($translation));
        $this->objectManager
            ->expects($this->once())
            ->method('flush');
        $this
            ->objectManager
            ->expects($this->once())
            ->method('clear');

        $this->translationManager->updateTranslationHistory($translation, true);
    }

    private function createRepository()
    {
        $this->repository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function createObjectManager()
    {
        $this->createRepository();
        $this->objectManager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')->getMock();
        $this->objectManager
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
    }

    /**
     * @return TranslationHistory|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTranslationHistory()
    {
        return $this->getMockBuilder('Asm\TranslationLoaderBundle\Entity\TranslationHistory')->getMock();
    }
}
