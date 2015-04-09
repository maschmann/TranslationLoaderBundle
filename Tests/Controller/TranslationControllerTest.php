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
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

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

        $this->createTemplating();
        $this->createFormFactory();
    }

    /**
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::__construct()
     * @covers \Asm\TranslationLoaderBundle\Controller\TranslationController::listAction()
     */
    public function testListAction()
    {
        $this->markTestSkipped();
        $this->controller = new TranslationController(
            $this->templating,
            $this->translationManager,
            $this->formFactory
        );

        $this->assertInstanceOf(
            '\Asm\TranslationLoaderBundle\Controller\TranslationController',
            $this->controller
        );
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
        $this->templating = new TemplatingMock();
    }

    private function createFormFactory()
    {
        $this->formFactory = new FormFactoryMock(
            $this->getMock(
                'Symfony\Component\Form\Form',
                [],
                [],
                'Form',
                false
            )
        );
    }
}


class TemplatingMock implements EngineInterface
{

    public function render($name, array $parameters = array())
    {
        // TODO: Implement render() method.
    }

    public function exists($name)
    {
        // TODO: Implement exists() method.
    }

    public function supports($name)
    {
        // TODO: Implement supports() method.
    }
}

class FormFactoryMock implements FormFactoryInterface
{

    private $form;

    public function __construct($form)
    {
        $this->form = $form;
    }

    public function create($type = 'form', $data = null, array $options = array())
    {
        return $this->form;
    }

    public function createNamed($name, $type = 'form', $data = null, array $options = array())
    {
        return $this->form;
    }

    public function createForProperty($class, $property, $data = null, array $options = array())
    {
        // FormBuilderInterface
        // TODO: Implement createForProperty() method.
    }

    public function createBuilder($type = 'form', $data = null, array $options = array())
    {
        // TODO: Implement createBuilder() method.
    }

    public function createNamedBuilder($name, $type = 'form', $data = null, array $options = array())
    {
        // TODO: Implement createNamedBuilder() method.
    }

    public function createBuilderForProperty($class, $property, $data = null, array $options = array())
    {
        // TODO: Implement createBuilderForProperty() method.
    }
}
