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

use Asm\TranslationLoaderBundle\Model\TranslationHistory as BaseTranslationHistory;

class TranslationHistoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TranslationHistory
     */
    private $translation;

    protected function setUp()
    {
        $this->translation = new TranslationHistory();
    }

    public function testDateOfChange()
    {
        $this->assertNull($this->translation->getDateOfChange());

        $now = new \DateTime();
        $this->translation->setDateOfChange($now);
        $this->assertEquals($now, $this->translation->getDateOfChange());
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

    public function testUserAction()
    {
        $this->assertNull($this->translation->getUserAction());

        $this->translation->setUserAction('foo');
        $this->assertEquals('foo', $this->translation->getUserAction());
    }

    public function testUserName()
    {
        $this->assertNull($this->translation->getUserName());

        $this->translation->setUserName('foo');
        $this->assertEquals('foo', $this->translation->getUserName());
    }
}

class TranslationHistory extends BaseTranslationHistory {
}
 