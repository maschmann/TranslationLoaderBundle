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

use Asm\TranslationLoaderBundle\Model\Translation;

/**
 * Class DumpTranslationFilesCommandTest
 *
 * @package Asm\TranslationLoaderBundle\Tests\Command
 * @author marc aschmann <maschmann@gmail.com>
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class DumpTranslationFilesCommandTest extends CommandTest
{
    private $translationWriter;

    protected function setUp()
    {
        parent::setUp();

        $this->translationWriter = $this->getMock('Symfony\Component\Translation\Writer\TranslationWriter');
        static::$kernel->getContainer()->set('translation.writer', $this->translationWriter);
    }

    public function testCommand()
    {
        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationsBy')
            ->with(array(
                'messageDomain' => 'foo',
                'transLocale'   => 'de_DE'
            ))
            ->will($this->returnValue($this->createTranslationsResult()));
        $this->translationWriter
            ->expects($this->exactly(2))
            ->method('writeTranslations')
            ->with($this->isInstanceOf('Symfony\Component\Translation\MessageCatalogue'));

        $this->execute(
            'asm:translations:dump',
            array(
                '--domain' => 'foo',
                '--locale' => 'de_DE',
                '--format' => 'yml',
            )
        );
    }

    public function testCommandWithoutArguments()
    {
        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationsBy')
            ->with(array(
                'messageDomain' => 'messages'
            ))
            ->will($this->returnValue($this->createTranslationsResult()));
        $this->translationWriter
            ->expects($this->exactly(2))
            ->method('writeTranslations')
            ->with($this->isInstanceOf('Symfony\Component\Translation\MessageCatalogue'));

        $this->execute('asm:translations:dump');
    }

    private function createTranslationsResult()
    {
        $translation1 = new DummyTranslation();
        $translation1->setTransLocale('de_DE');
        $translation2 = new DummyTranslation();
        $translation1->setTransLocale('fr_FR');

        return array($translation1, $translation2);
    }
}

class DummyTranslation extends Translation
{
}
