<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\DependencyInjection;

use Asm\TranslationLoaderBundle\DependencyInjection\AsmTranslationLoaderExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AsmTranslatioLoaderExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AsmTranslationLoaderExtension
     */
    private $extension;

    protected function setUp()
    {
        $this->extension = new AsmTranslationLoaderExtension();
    }

    public function testEmptyConfiguration()
    {
        $container = new ContainerBuilder();
        $this->extension->load(array(), $container);

        $this->assertTrue($container->hasDefinition('asm_translation_loader.translation_manager'));

        // ensures that the translation manager service is injected into the
        // database translation loader service
        $this->assertEquals(
            'asm_translation_loader.translation_manager',
            $container->getDefinition('translation.loader.db')->getArgument(0)
        );
    }
}
