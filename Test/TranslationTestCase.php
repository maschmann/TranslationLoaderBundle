<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Test;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Base class for functional tests.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class TranslationTestCase extends WebTestCase
{
    /**
     * @var Application
     */
    protected $application;

    /**
     * @var \Asm\TranslationLoaderBundle\Model\TranslationManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $translationManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        if (null === static::$kernel) {
            static::$kernel = static::createKernel();
        }

        static::$kernel->boot();

        $this->createServices();

        static::$kernel->boot();
        $this->application = new Application(static::$kernel);
    }

    /**
     * {@inheritdoc}
     */
    protected function createServices()
    {
        $container = static::$kernel->getContainer();

        $this->translationManager = $this->getMockBuilder('Asm\TranslationLoaderBundle\Model\TranslationManager')
            ->disableOriginalConstructor()
            ->getMock();

        $container->set(
            'asm_translation_loader.translation_manager',
            $this->translationManager
        );
    }
}
