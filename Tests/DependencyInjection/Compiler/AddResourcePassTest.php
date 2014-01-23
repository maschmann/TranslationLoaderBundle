<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\DependencyInjection\Compiler;

use Asm\TranslationLoaderBundle\DependencyInjection\Compiler\AddResourcePass;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AddResourcePassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AddResourcePass
     */
    private $compilerPass;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $translator;

    protected function setUp()
    {
        $this->compilerPass = new AddResourcePass();
        $this->createContainer();
    }

    public function testWithoutLocales()
    {
        $container = $this->createContainer();
        $this->translator
            ->expects($this->never())
            ->method('addMethodCall');

        $this->registerConfig($container, array());
        $this->compilerPass->process($container);
    }

    public function testLocaleWithoutDomain()
    {
        $container = $this->createContainer();
        $this->translator
            ->expects($this->once())
            ->method('addMethodCall');

        $this->registerConfig($container, array('en' => array(null)));
        $this->compilerPass->process($container);
    }

    public function testLocaleWithOneDomain()
    {
        $container = $this->createContainer();
        $this->translator
            ->expects($this->once())
            ->method('addMethodCall');

        $this->registerConfig($container, array('en' => array('foo')));
        $this->compilerPass->process($container);
    }

    public function testLocaleWithMultipleDomains()
    {
        $container = $this->createContainer();
        $this->translator
            ->expects($this->exactly(2))
            ->method('addMethodCall');

        $this->registerConfig($container, array('en' => array('foo', 'bar')));
        $this->compilerPass->process($container);
    }

    public function testMultipleLocalesWithMultipleDomains()
    {
        $container = $this->createContainer();
        $this->translator
            ->expects($this->exactly(6))
            ->method('addMethodCall');

        $this->registerConfig($container, array(
            'en' => array('foo', 'bar'),
            'de' => array('baz'),
            'fr' => array('x', 'y', 'z')
        ));
        $this->compilerPass->process($container);
    }

    public function testWithoutTranslator()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->expects($this->once())
            ->method('findDefinition')
            ->with('translator.default')
            ->will($this->returnValue(null));

        $this->registerConfig($container, array(
            'en' => array('foo', 'bar'),
            'de' => array('baz'),
            'fr' => array('x', 'y', 'z')
        ));
        $this->compilerPass->process($container);
    }

    public function testWithoutTranslatorAndWithoutLocales()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');
        $container
            ->expects($this->once())
            ->method('findDefinition')
            ->with('translator.default')
            ->will($this->returnValue(null));
        $this->translator
            ->expects($this->never())
            ->method('addMethodCall');

        $this->registerConfig($container, array());
        $this->compilerPass->process($container);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private function createContainer()
    {
        $container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->translator = $this->getMock('\Symfony\Component\DependencyInjection\Definition');
        $container
            ->expects($this->any())
            ->method('findDefinition')
            ->with('translator.default')
            ->will($this->returnValue($this->translator));

        return $container;
    }

    private function registerConfig($container, array $config)
    {
        $container
            ->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue($config));
    }
}
 