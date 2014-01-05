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

use Asm\TranslationLoaderBundle\Model\Translation as BaseTranslation;

class TranslationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Translation
     */
    private $translation;

    protected function setUp()
    {
        $this->translation = new Translation();
    }

    public function testTransKey() {
        $this->assertNull($this->translation->getTransKey());

        $this->translation->setTransKey('foo');
        $this->assertEquals('foo', $this->translation->getTransKey());
    }

    public function testTransLocale()
    {
        $this->assertNull($this->translation->getTransLocale());

        $this->translation->setTransLocale('de');
        $this->assertEquals('de', $this->translation->getTransLocale());
    }

    public function testMessageDomain()
    {
        $this->assertNull($this->translation->getMessageDomain());

        $this->translation->setMessageDomain('messages');
        $this->assertEquals('messages', $this->translation->getMessageDomain());
    }

    public function testTranslation()
    {
        $this->assertNull($this->translation->getTranslation());

        $this->translation->setTranslation('foo');
        $this->assertEquals('foo', $this->translation->getTranslation());
    }

    public function testDateCreated()
    {
        $this->assertNull($this->translation->getDateCreated());

        $now = new \DateTime();
        $this->translation->setDateCreated($now);
        $this->assertEquals($now, $this->translation->getDateCreated());
    }

    public function testDateUpdated()
    {
        $this->assertNull($this->translation->getDateUpdated());

        $now = new \DateTime();
        $this->translation->setDateUpdated($now);
        $this->assertEquals($now, $this->translation->getDateUpdated());
    }
}

class Translation extends BaseTranslation {
}
 