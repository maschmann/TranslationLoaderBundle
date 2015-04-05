<?php
/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Asm\TranslationLoaderBundle\Tests\Controller;

use \Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TranslationControllerTest
 *
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationControllerTest extends WebTestCase
{
    public function testListAction()
    {
        $this->markTestSkipped();
        /*$client = static::createClient();
        $crawler = $client->request('GET', '/translation/list');

        $this->assertTrue($client->getResponse()->isSuccessful());*/
    }

    public function testFormAction()
    {
        $this->markTestSkipped();
        /*$client = static::createClient();
        $crawler = $client->request('GET', '/translation/form');

        $this->assertTrue($client->getResponse()->isSuccessful());*/
    }

    public function testCreateAction()
    {
        $this->markTestSkipped();
        /*$client = static::createClient();
        $crawler = $client->request('POST', '/translation/create');

        $this->assertTrue($client->getResponse()->isSuccessful());*/
    }

    public function testUpdateAction()
    {
        $this->markTestSkipped();
        /*$client = static::createClient();
        $crawler = $client->request('POST', '/translation/update');

        $this->assertTrue($client->getResponse()->isSuccessful());*/
    }

    public function testDeleteAction()
    {
        $this->markTestSkipped();
        /*$client = static::createClient();
        $crawler = $client->request('POST', '/translation/delete');

        $this->assertTrue($client->getResponse()->isSuccessful());*/
    }
}
