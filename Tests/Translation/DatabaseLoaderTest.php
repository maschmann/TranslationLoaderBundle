<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Tests\Translation
 */
namespace Asm\TranslationLoaderBundle\Tests\Translation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Asm\TranslationLoaderBundle\Translation;

/**
 * Class DatabaseLoaderTest
 *
 * @package Asm\TranslationLoaderBundle\Tests\Translation
 * @author marc aschmann <maschmann@gmail.com>
 */
class DatabaseLoaderTest extends WebTestCase
{
    public function testExecute()
    {
        $kernel = $this->createKernel();
        $kernel->boot();
    }
}
