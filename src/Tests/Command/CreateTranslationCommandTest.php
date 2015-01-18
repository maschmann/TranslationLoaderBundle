<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\Command;

/**
 * Class CreateTranslationCommandTest
 *
 * @package Asm\TranslationLoaderBundle\Tests\Command
 * @author marc aschmann <maschmann@gmail.com>
 */
class CreateTranslationCommandTest extends CommandTest
{
    public function testCommand()
    {
        /** @var \Asm\TranslationLoaderBundle\Model\Translation|\PHPUnit_Framework_MockObject_MockObject $translation */
        $translation = $this->getMock('Asm\TranslationLoaderBundle\Model\Translation');
        $translation->setTransKey('foo');
        $translation->setTransLocale('de_DE');
        $translation->setMessageDomain('messages');

        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationBy')
            ->with(array(
                'transKey'      => 'foo',
                'transLocale'   => 'de_DE',
                'messageDomain' => 'messages',
            ))
            ->will($this->returnValue(null));
        $this->translationManager
            ->expects($this->once())
            ->method('createTranslation')
            ->will($this->returnValue($translation));
        $translation->expects($this->once())
            ->method('setTranslation')
            ->with('bar');
        $this->translationManager
            ->expects($this->once())
            ->method('updateTranslation')
            ->with($this->equalTo($translation));

        $this->execute(
            'asm:translations:create',
            array(
                'key'         => 'foo',
                'locale'      => 'de_DE',
                'translation' => 'bar',
                'domain'      => 'messages',
            )
        );
    }
    public function testCommandWithExistingTranslation()
    {
        $translation = $this->getMock('Asm\TranslationLoaderBundle\Model\Translation');
        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationBy')
            ->with(array(
                'transKey'      => 'foo',
                'transLocale'   => 'de_DE',
                'messageDomain' => 'messages',
            ))
            ->will($this->returnValue($translation));
        $this->translationManager
            ->expects($this->never())
            ->method('createTranslation');
        $translation->expects($this->once())
            ->method('setTranslation')
            ->with('bar');
        $this->translationManager
            ->expects($this->once())
            ->method('updateTranslation')
            ->with($this->equalTo($translation));

        $this->execute(
            'asm:translations:create',
            array(
                'key'         => 'foo',
                'locale'      => 'de_DE',
                'translation' => 'bar',
                'domain'      => 'messages',
            )
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCommandWithoutArguments()
    {
        //$command = $this->application->find('asm:translations:create');
        $this->execute('asm:translations:create');
    }
}
