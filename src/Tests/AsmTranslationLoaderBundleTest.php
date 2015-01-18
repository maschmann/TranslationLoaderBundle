<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests;

use Asm\TranslationLoaderBundle\AsmTranslationLoaderBundle;
use Asm\TranslationLoaderBundle\DependencyInjection\Compiler\LegacyRegisterListenersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\RegisterListenersPass;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class AsmTranslationLoaderBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AsmTranslationLoaderBundle
     */
    private $bundle;

    protected function setUp()
    {
        $this->bundle = new AsmTranslationLoaderBundle();
    }

    public function testBuild()
    {
        $container = new ContainerBuilder();
        $this->bundle->build($container);
        $passes = $container->getCompilerPassConfig()->getBeforeRemovingPasses();

        if (class_exists('Symfony\Component\HttpKernel\DependencyInjection\RegisterListenersPass')) {
            $expectedPass = new RegisterListenersPass(
                'asm_translation_loader.event_dispatcher',
                'asm_translation_loader.event_listener',
                'asm_translation_loader.event_subscriber'
            );
        } else {
            $expectedPass = new LegacyRegisterListenersPass(
                'asm_translation_loader.event_dispatcher',
                'asm_translation_loader.event_listener',
                'asm_translation_loader.event_subscriber'
            );
        }

        $this->assertEquals($expectedPass, $passes[0]);
    }
}
