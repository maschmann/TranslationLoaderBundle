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

use Asm\TranslationLoaderBundle\Doctrine\TranslationManager;
use Asm\TranslationLoaderBundle\Entity\Translation;
use Asm\TranslationLoaderBundle\Tests\Model\TranslationManagerTest as BaseTranslationManagerTest;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;

class TranslationManagerTest extends BaseTranslationManagerTest
{
    /**
     * @var EntityRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManager;

    public function testCreateTranslation()
    {
        $translation = $this->translationManager->createTranslation();
        $this->assertInstanceOf('Asm\TranslationLoaderBundle\Entity\Translation', $translation);
    }

    public function testFindTranslationBy()
    {
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(
                array(
                    'transLocale'   => 'fr_FR',
                    'transKey'      => 'foo',
                )
            ));

        $this->translationManager->findTranslationBy(array(
            'transLocale' => 'fr_FR',
            'transKey'    => 'foo',
        ));
    }

    public function testFindAllTranslations()
    {
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo(array()));

        $this->translationManager->findAllTranslations();
    }

    public function testFindTranslationsBy()
    {
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo(
                array(
                    'transLocale'   => 'fr_FR',
                    'transKey'      => 'foo',
                )
            ));

        $this->translationManager->findTranslationsBy(array(
            'transLocale' => 'fr_FR',
            'transKey'    => 'foo',
        ));
    }

    public function testFindTranslationsByLocaleAndDomain()
    {
        $this->repository
            ->expects($this->once())
            ->method('findBy')
            ->with($this->equalTo(
                array(
                    'transLocale'   => 'en',
                    'messageDomain' => 'messages',
                )
            ));

        $this->translationManager->findTranslationsByLocaleAndDomain('en', 'messages');
    }

    public function testUpdateTranslation()
    {
        $translation = $this->createTranslation();
        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($translation));
        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->translationManager->updateTranslation($translation);
    }

    public function testRemoveTranslation()
    {
        $translation = $this->createTranslation();
        $this->objectManager
            ->expects($this->once())
            ->method('remove');
        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->translationManager->removeTranslation($translation);
    }

    protected function createTranslationManager()
    {
        $this->createObjectManager();

        return new TranslationManager(
            $this->objectManager,
            'Asm\TranslationLoaderBundle\Entity\Translation',
            $this->eventDispatcher
        );
    }

    protected function createFreshTranslation()
    {
        $translation = new Translation();
        $this
            ->objectManager
            ->expects($this->any())
            ->method('contains')
            ->with($translation)
            ->willReturn(false);

        return $translation;
    }

    protected function createNonFreshTranslation()
    {
        $translation = new Translation();
        $this
            ->objectManager
            ->expects($this->any())
            ->method('contains')
            ->with($translation)
            ->willReturn(true);

        return $translation;
    }

    private function createRepository()
    {
        $this->repository = $this->getMock(
            'Doctrine\ORM\EntityRepository',
            array(),
            array(),
            '',
            false
        );
    }

    private function createObjectManager()
    {
        $this->createRepository();
        $this->objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->objectManager
            ->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));
    }

    /**
     * @return Translation|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createTranslation()
    {
        return $this->getMock('Asm\TranslationLoaderBundle\Entity\Translation');
    }
}
