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

use Asm\TranslationLoaderBundle\Controller\TranslationController;
use Asm\TranslationLoaderBundle\Test\TranslationTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TranslationControllerTest
 *
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationControllerTest extends TranslationTestCase
{
    private $templating;

    private $formFactory;

    private $controller;

    protected function setUp()
    {
        parent::setUp();

        //$this->createTemplating();
        //$this->createFormFactory();
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::__construct()
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::listAction()
     */
    public function testListAction()
    {
        $this->markTestSkipped();
        /*$this->controller = new TranslationController(
            $this->templating,
            $this->translationManager,
            $this->formFactory
        );

        $this->assertInstanceOf(
            '\Asm\TranslationLoaderBundle\Controller\TranslationController',
            $this->controller
        );*/
    }

    public function testFormAction()
    {
        $this->markTestSkipped();
    }

    public function testCreateAction()
    {
        $this->markTestSkipped();
    }

    public function testUpdateAction()
    {
        $this->markTestSkipped();
    }

    public function testDeleteAction()
    {
        $this->markTestSkipped();
    }

    private function createTemplating()
    {
        $this->templating = $this->getMock(
            '\Symfony\Component\Form\FormFactory'
        );
    }

    private function createFormFactory()
    {
        $this->formFactory = $this->getMock(
            '\Symfony\Component\Form\FormFactory'
        );
    }
}
