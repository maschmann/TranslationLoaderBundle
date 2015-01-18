<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\Translation;

use Asm\TranslationLoaderBundle\Model\Translation;
use Asm\TranslationLoaderBundle\Model\TranslationManager;
use Asm\TranslationLoaderBundle\Translation\DatabaseLoader;

/**
 * Class DatabaseLoaderTest
 *
 * @package Asm\TranslationLoaderBundle\Tests\Translation
 * @author marc aschmann <maschmann@gmail.com>
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 * @uses Asm\TranslationLoaderBundle\Translation\DatabaseLoader
 */
class DatabaseLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TranslationManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $translationManager;

    /**
     * @var DatabaseLoader
     */
    private $databaseLoader;

    /**
     * @var \Symfony\Component\Translation\MessageCatalogue
     */
    private $catalogue;

    protected function setUp()
    {
        $this->createTranslationManager();
        $this->databaseLoader = new DatabaseLoader($this->translationManager);
        $this->catalogue = $this->databaseLoader->load(null, 'en_US', 'messages');
    }

    public function testLoad()
    {
        $this->assertEquals('foo translated', $this->catalogue->get('foo'));
        $this->assertEquals('bar translated', $this->catalogue->get('bar'));
    }

    public function testNonExistentKey()
    {
        $this->assertFalse($this->catalogue->has('baz'));
    }

    public function testMessageDomains()
    {
        $this->assertTrue(in_array('messages', $this->catalogue->getDomains()));
        $this->assertFalse(in_array('foo', $this->catalogue->getDomains()));
    }

    private function createTranslationManager()
    {
        $this->translationManager = $this->getMock(
            'Asm\TranslationLoaderBundle\Model\TranslationManager',
            array(),
            array(),
            '',
            false
        );
        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationsByLocaleAndDomain')
            ->with('en_US', 'messages')
            ->will($this->returnValue($this->createTranslationResult()));
    }

    private function createTranslationResult()
    {
        $translation1 = new DummyTranslation();
        $translation1->setTransKey('foo');
        $translation1->setTranslation('foo translated');

        $translation2 = new DummyTranslation();
        $translation2->setTransKey('bar');
        $translation2->setTranslation('bar translated');

        return array($translation1, $translation2);
    }
}

class DummyTranslation extends Translation
{
}
