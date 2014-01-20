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
     * @var \Symfony\Component\DependencyInjection\ContainerBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

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
        $this->translator
            ->expects($this->never())
            ->method('addMethodCall');

        $this->registerConfig(array());
        $this->compilerPass->process($this->container);
    }

    public function testLocaleWithoutDomain()
    {
        $this->translator
            ->expects($this->once())
            ->method('addMethodCall');

        $this->registerConfig(array('en' => array(null)));
        $this->compilerPass->process($this->container);
    }

    public function testLocaleWithOneDomain()
    {
        $this->translator
            ->expects($this->once())
            ->method('addMethodCall');

        $this->registerConfig(array('en' => array('foo')));
        $this->compilerPass->process($this->container);
    }

    public function testLocaleWithMultipleDomains()
    {
        $this->translator
            ->expects($this->exactly(2))
            ->method('addMethodCall');

        $this->registerConfig(array('en' => array('foo', 'bar')));
        $this->compilerPass->process($this->container);
    }

    public function testMultipleLocalesWithMultipleDomains()
    {
        $this->translator
            ->expects($this->exactly(6))
            ->method('addMethodCall');

        $this->registerConfig(array(
            'en' => array('foo', 'bar'),
            'de' => array('baz'),
            'fr' => array('x', 'y', 'z')
        ));
        $this->compilerPass->process($this->container);
    }

    private function createContainer()
    {
        $this->container = $this->getMock('\Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->translator = $this->getMock('\Symfony\Component\DependencyInjection\Definition');
        $this->container
            ->expects($this->any())
            ->method('findDefinition')
            ->with('translator.default')
            ->will($this->returnValue($this->translator));
    }

    private function registerConfig(array $config)
    {
        $this->container
            ->expects($this->any())
            ->method('getParameter')
            ->will($this->returnValue($config));
    }
}
 