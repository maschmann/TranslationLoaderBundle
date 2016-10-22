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
use Asm\TranslationLoaderBundle\Entity\Translation;
use Asm\TranslationLoaderBundle\Test\TranslationTestCase;
use Symfony\Component\Form\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TranslationControllerTest
 *
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationControllerTest extends TranslationTestCase
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var \Asm\TranslationLoaderBundle\Controller\TranslationController
     */
    private $controller;

    protected function setUp()
    {
        parent::setUp();

        $this->createTemplating();
        $this->createFormFactory();

        $this->controller = new TranslationController(
            $this->templating,
            $this->translationManager,
            $this->formFactory
        );
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::__construct()
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::listAction()
     */
    public function testListAction()
    {
        $this->assertInstanceOf(
            '\Asm\TranslationLoaderBundle\Controller\TranslationController',
            $this->controller
        );

        $translation = new Translation();
        $translation
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('testing the translations');
        $count = new \PHPUnit_Framework_MockObject_Matcher_InvokedCount(2);


        $this->translationManager
            ->expects($count)
            ->method('getTranslationList')
            ->will($this->returnValue(
                array(
                    $translation,
                )
            ));

        $this->expectRender(
            2,
            new Response(
                'Loads of response data',
                200
            )
        );

        // full list
        $request = new Request();
        $response = $this->controller->listAction($request);
        $this->assertTrue($response->isSuccessful());

        // simulate ajax request
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->setMethod('GET');
        $response = $this->controller->listAction($request);

        $this->assertTrue($response->isSuccessful());
    }

    public function testFormActionEmpty()
    {
        $form = $this
            ->getMockBuilder('Symfony\Tests\Component\Form\FormInterface')
            ->setMethods(array('createView'))
            ->getMock();
        $form
            ->expects($this->once())
            ->method('createView');

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));



        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationBy')
            ->with(
                array(
                    'transKey'      => '',
                    'transLocale'   => '',
                    'messageDomain' => '',
                )
            )
            ->will($this->returnValue(null));

        $this->expectRender(
            1,
            new Response(
                'Loads of response data',
                200
            )
        );

        // new translation
        $response = $this->controller->formAction();
        $this->assertTrue($response->isSuccessful());
    }

    public function testFormActionPrefilled()
    {
        $form = $this
            ->getMockBuilder('Symfony\Tests\Component\Form\FormInterface')
            ->setMethods(array('createView'))
            ->getMock();
        $form
            ->expects($this->once())
            ->method('createView');

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $translation = new Translation();
        $translation
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('testing the translations');

        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationBy')
            ->with(
                array(
                    'transKey'      => 'testing',
                    'transLocale'   => 'de_DE',
                    'messageDomain' => 'messages',
                )
            )
            ->will($this->returnValue($translation));

        $this->expectRender(
            1,
            new Response(
                'Loads of response data',
                200
            )
        );

        // with existing translation
        $response = $this->controller->formAction('testing', 'de_DE', 'messages');
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::createAction()
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::handleForm()
     */
    public function testCreateActionValidForm()
    {
        $request = new Request();
        $translationCreate = new Translation();
        $translationCreate
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('YaddaYadda!');

        $translationNew = new Translation();

        $form = $this
            ->getMockBuilder('Symfony\Tests\Component\Form\FormInterface')
            ->setMethods(
                array(
                    'createView',
                    'handleRequest',
                    'isValid',
                    'getData',
                )
            )
            ->getMock();
        $form
            ->expects($this->once())
            ->method('handleRequest');

        $form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $form
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($translationCreate));

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->translationManager
            ->expects($this->once())
            ->method('updateTranslation');

        $response = $this->controller->createAction($request);
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::handleForm()
     */
    public function testCreateActionInvalidForm()
    {
        $request = new Request();
        $form = $this
            ->getMockBuilder('Symfony\Tests\Component\Form\FormInterface')
            ->setMethods(
                array(
                    'createView',
                    'handleRequest',
                    'isValid',
                    'getData',
                )
            )
            ->getMock();
        $form
            ->expects($this->once())
            ->method('handleRequest');

        $form
            ->expects($this->once())
            ->method('createView');

        $form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $response = $this->controller->createAction($request);
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::handleForm()
     */
    public function testCreateActionException()
    {
        $request = new Request();
        $translationUpdate = new Translation();
        $translationUpdate
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('YaddaYadda!');

        $translationOld = new Translation();
        $translationOld
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('testing the translations');

        $form = $this
            ->getMockBuilder('Symfony\Tests\Component\Form\FormInterface')
            ->setMethods(
                array(
                    'createView',
                    'handleRequest',
                    'isValid',
                    'getData',
                )
            )
            ->getMock();
        $form
            ->expects($this->once())
            ->method('handleRequest');

        $form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $form
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($translationUpdate));

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->translationManager
            ->expects($this->once())
            ->method('updateTranslation')
            ->will($this->throwException(new \ErrorException('testing errors')));

        $response = $this->controller->createAction($request);
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::updateAction()
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::handleForm()
     */
    public function testUpdateActionValidForm()
    {
        $request = new Request();
        $translationUpdate = new Translation();
        $translationUpdate
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('YaddaYadda!');

        $translationOld = new Translation();
        $translationOld
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('testing the translations');

        $form = $this
            ->getMockBuilder('Symfony\Tests\Component\Form\FormInterface')
            ->setMethods(
                array(
                    'createView',
                    'handleRequest',
                    'isValid',
                    'getData',
                )
            )
            ->getMock();
        $form
            ->expects($this->once())
            ->method('handleRequest');

        $form
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $form
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($translationUpdate));

        $this->formFactory
            ->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationBy')
            ->with(
                array(
                    'transKey'      => 'testing',
                    'transLocale'   => 'de_DE',
                    'messageDomain' => 'messages',
                )
            )
            ->will($this->returnValue($translationOld));

        $response = $this->controller->updateAction($request);
        $this->assertTrue($response->isSuccessful());
    }

    public function testDeleteAction()
    {
        $request = new Request();
        $request->request->add(
            array(
                'key' => 'testing',
                'locale' => 'de_DE',
                'domain' => 'messages',
            )
        );

        $translation = new Translation();
        $translation
            ->setTransLocale('de_DE')
            ->setTransKey('testing')
            ->setMessageDomain('messages')
            ->setTranslation('YaddaYadda!');

        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationBy')
            ->with(
                array(
                    'transKey'      => 'testing',
                    'transLocale'   => 'de_DE',
                    'messageDomain' => 'messages',
                )
            )
            ->will($this->returnValue($translation));

        $response = $this->controller->deleteAction($request);
        $this->assertTrue($response->isSuccessful());
    }

    public function testDeleteActionNotFound()
    {
        $request = new Request();
        $request->request->add(
            array(
                'key' => 'testing',
                'locale' => '',
                'domain' => 'messages',
            )
        );

        $this->translationManager
            ->expects($this->once())
            ->method('findTranslationBy')
            ->with(
                array(
                    'transKey'      => 'testing',
                    'transLocale'   => '',
                    'messageDomain' => 'messages',
                )
            )
            ->will($this->returnValue(null));

        $response = $this->controller->deleteAction($request);
        $this->assertTrue($response->isNotFound());
    }

    private function createTemplating()
    {
        $this->templating = $this->getMockBuilder('Symfony\Component\Templating\EngineInterface')->getMock();
    }

    private function expectRender($times, $response)
    {
        $count = new \PHPUnit_Framework_MockObject_Matcher_InvokedCount($times);

        $this->templating
            ->expects($count)
            ->method('render')
            ->will(
                $this->returnValue(
                    $response
                )
            );
    }

    private function createFormFactory()
    {
        $this->formFactory = $this->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')->getMock();
    }
}
