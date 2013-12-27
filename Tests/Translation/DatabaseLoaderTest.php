<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Tests\Translation
 */
namespace Asm\TranslationLoaderBundle\Tests\Translation;

use Asm\TranslationLoaderBundle\TestCase\DatabaseTestCase;
use Asm\TranslationLoaderBundle\Translation\DatabaseLoader;

/**
 * Class DatabaseLoaderTest
 *
 * @package Asm\TranslationLoaderBundle\Tests\Translation
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Asm\TranslationLoaderBundle\TestCase\DatabaseTestCase
 * @uses Asm\TranslationLoaderBundle\Translation\DatabaseLoader
 */
class DatabaseLoaderTest extends DatabaseTestCase
{

    /**
     * load translations from db to catalogue
     *
     * @covers Asm\TranslationLoaderBundle\Translation\DatabaseLoader::__construct
     */
    public function testLoadDefault()
    {
        /** @var \Asm\TranslationLoaderBundle\Translation\DatabaseLoader $loader */
        $loader = $this->getContainer()->get('translation.loader.db');
        /** @var \Symfony\Component\Translation\MessageCatalogue $catalogue */
        $catalogue = $loader->load(null, 'en_US', 'messages');
        $translations = $catalogue->all();
        $this->assertTrue(is_array($translations));
        $this->assertEquals(7, count($translations), 'Not all translations for "en_US", domain "messages" were loaded from database.');
    }


    /**
     * load specific translation key and check in message catalogue
     */
    public function testLoadKey()
    {
        /** @var \Asm\TranslationLoaderBundle\Translation\DatabaseLoader $loader */
        $loader = $this->getContainer()->get('translation.loader.db');
        /** @var \Symfony\Component\Translation\MessageCatalogue $catalogue */
        $catalogue = $loader->load('test1', 'en_US', 'messages');
        $this->assertTrue($catalogue->has('test1'), 'Key "test1" was not found in MessageCatalogoue.');
    }


    /**
     * test if specific domain has been loaded from db
     */
    public function testLoadDomain()
    {
        /** @var \Asm\TranslationLoaderBundle\Translation\DatabaseLoader $loader */
        $loader = $this->getContainer()->get('translation.loader.db');
        /** @var \Symfony\Component\Translation\MessageCatalogue $catalogue */
        $catalogue = $loader->load(null, 'en_US', 'test');
        $domains = $catalogue->getDomains();
        $this->assertTrue(in_array('test', $domains), 'Message domain "test" was not found in MessageCatalogue.');
    }

}
