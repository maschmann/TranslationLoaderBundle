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
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class AsmTranslatioLoaderExtensionTest extends AbstractExtensionTestCase
{
    public function testEmptyConfiguration()
    {
        $this->load();

        $this->assertContainerBuilderHasService('asm_translation_loader.translation_manager');
        $this->assertContainerBuilderHasService(
            'asm_translation_loader.file_loader_resolver',
            'Asm\TranslationLoaderBundle\Translation\FileLoaderResolver'
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'translation.loader.db',
            0,
            'asm_translation_loader.translation_manager'
        );
    }

    public function testWithNullTranslationLoaders()
    {
        $this->load(array('loaders' => null));

        $this->assertContainerBuilderHasParameter(
            'asm_translation_loader.translation_loaders',
            array(
                'xlf' => 'translation.loader.xliff',
                'yaml' => 'translation.loader.yml',
            )
        );
    }

    public function testWithAdditionalTranslationLoader()
    {
        $this->load(array('loaders' => array('foo' => 'translation.loader.bar')));

        $this->assertContainerBuilderHasParameter(
            'asm_translation_loader.translation_loaders',
            array(
                'foo' => 'translation.loader.bar',
                'xlf' => 'translation.loader.xliff',
                'yaml' => 'translation.loader.yml',
            )
        );
    }

    public function testWithReplacedDefaultTranslationLoader()
    {
        $this->load(array('loaders' => array('yaml' => 'translation.loader.foo')));

        $this->assertContainerBuilderHasParameter(
            'asm_translation_loader.translation_loaders',
            array(
                'xlf' => 'translation.loader.xliff',
                'yaml' => 'translation.loader.foo',
            )
        );
    }

    protected function getContainerExtensions()
    {
        return array(new AsmTranslationLoaderExtension());
    }
}
