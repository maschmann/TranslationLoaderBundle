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

use org\bovigo\vfs\vfsStream;

/**
 * Class GenerateDummyFilesCommandTest
 *
 * @package Asm\TranslationLoaderBundle\Tests\Command
 * @author marc aschmann <maschmann@gmail.com>
 */
class GenerateDummyFilesCommandTest extends CommandTest
{
    private $root;
    private $filesystem;
    private $translationDir;

    protected function setUp()
    {
        parent::setUp();
        $this->root = vfsStream::setup('test');
        $this->translationDir = self::$kernel->getRootDir() . '/Resources/translations/';

        $this->filesystem = new SpyFilesystem();
        $this->filesystem->setRoot($this->root);
        static::$kernel->getContainer()->set('filesystem', $this->filesystem);
    }

    public function testCommand()
    {
        $this->translationManager
            ->expects($this->once())
            ->method('findAllTranslations')
            ->will($this->returnValue($this->createTranslationsResult()));

        $this->assertFalse($this->root->hasChild($this->translationDir . 'messages.de_DE.db'));
        $this->assertFalse($this->root->hasChild($this->translationDir . 'messages.fr_FR.db'));

        $this->execute('asm:translations:dummy');

        $this->assertTrue($this->root->hasChild($this->translationDir . 'messages.de_DE.db'));
        $this->assertTrue($this->root->hasChild($this->translationDir . 'messages.fr_FR.db'));
    }

    private function createTranslationsResult()
    {
        $translation1 = new DummyTranslation();
        $translation1->setMessageDomain('messages');
        $translation1->setTransLocale('de_DE');
        $translation2 = new DummyTranslation();
        $translation2->setMessageDomain('messages');
        $translation2->setTransLocale('fr_FR');

        return array($translation1, $translation2);
    }
}

class SpyFilesystem extends \Symfony\Component\Filesystem\Filesystem {
    private $root;

    public function setRoot($root)
    {
        $this->root = $root;
    }

    public function exists($check)
    {
        return false;
    }

    public function mkdir($dirs, $mode = 777)
    {
        return true;
    }

    public function touch($files, $time = null, $atime = null)
    {
        $this->root->addChild(
            vfsStream::newFile($files)
        );
    }
}
