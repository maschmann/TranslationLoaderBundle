<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\Config\Resource;

use Asm\TranslationLoaderBundle\Config\Resource\TranslationManagerResource;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationManagerResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TranslationManagerResource
     */
    private $translationManagerResource;

    /**
     * @var \Asm\TranslationLoaderBundle\Translation\DatabaseLoader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $databaseLoader;

    protected function setUp()
    {
        $this->createDatabaseLoader();
        $this->translationManagerResource = new TranslationManagerResource($this->databaseLoader);
    }

    public function testIsFresh()
    {
        $this->databaseLoader
            ->expects($this->once())
            ->method('isFresh')
            ->will($this->returnValue(true));

        $this->assertTrue(is_bool($this->translationManagerResource->isFresh(time())));
    }

    public function testGetResource()
    {
        $this->assertSame($this->databaseLoader, $this->translationManagerResource->getResource());
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Config\Resource\TranslationManagerResource::__toString()
     */
    public function testToString()
    {
        assertEquals('DatabaseLoader', $this->translationManagerResource->__toString());
    }

    private function createDatabaseLoader()
    {
        $this->databaseLoader = $this->getMock(
            '\Asm\TranslationLoaderBundle\Translation\DatabaseLoader',
            array(
                'isFresh'
            ),
            array(),
            '',
            false
        );
    }
}
