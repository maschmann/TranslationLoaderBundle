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

use Asm\TranslationLoaderBundle\DependencyInjection\Compiler\RegisterFileLoadersPass;
use Asm\TranslationLoaderBundle\Model\TranslationInterface;
use Asm\TranslationLoaderBundle\Model\TranslationManagerInterface;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class RegisterFileLoadersPassTest extends AbstractCompilerPassTestCase
{
    public function testCsvFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\CsvFileLoader',
            $fileLoaderResolver->resolveLoader('csv')
        );
    }

    public function testDatFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\IcuDatFileLoader',
            $fileLoaderResolver->resolveLoader('dat')
        );
    }

    public function testDatabaseLoaderIsNotRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertNull($fileLoaderResolver->resolveLoader('db'));
    }

    public function testIniFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\IniFileLoader',
            $fileLoaderResolver->resolveLoader('ini')
        );
    }

    public function testMoFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\MoFileLoader',
            $fileLoaderResolver->resolveLoader('mo')
        );
    }

    public function testPhpFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\PhpFileLoader',
            $fileLoaderResolver->resolveLoader('php')
        );
    }

    public function testPoFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\PoFileLoader',
            $fileLoaderResolver->resolveLoader('po')
        );
    }

    public function testQtFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\QtFileLoader',
            $fileLoaderResolver->resolveLoader('qt')
        );
    }

    public function testResFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\IcuResFileLoader',
            $fileLoaderResolver->resolveLoader('res')
        );
    }

    public function testXliffFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\XliffFileLoader',
            $fileLoaderResolver->resolveLoader('xliff')
        );
    }

    public function testYmlFileloaderIsRegistered()
    {
        $this->bootstrapContainer();
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\YamlFileLoader',
            $fileLoaderResolver->resolveLoader('yml')
        );
    }

    public function testAdditionalFileExtensionsAreRegistered()
    {
        $this->bootstrapContainer();
        $this->setParameter('asm_translation_loader.translation_loaders', array(
            'xlf' => 'translation.loader.xliff',
            'yaml' => 'translation.loader.yml',
        ));
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\XliffFileLoader',
            $fileLoaderResolver->resolveLoader('xlf')
        );
        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\YamlFileLoader',
            $fileLoaderResolver->resolveLoader('yaml')
        );
    }

    public function testStandardFileLoadersCanBeReplaced()
    {
        $this->bootstrapContainer();
        $this->setParameter('asm_translation_loader.translation_loaders', array(
            'yml' => 'translation.loader.csv',
        ));
        $this->compile();
        $fileLoaderResolver = $this->getFileLoaderResolver();

        $this->assertInstanceOf(
            'Symfony\Component\Translation\Loader\CsvFileLoader',
            $fileLoaderResolver->resolveLoader('yml')
        );
    }

    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterFileLoadersPass());
    }

    private function bootstrapContainer()
    {
        $fileLoaderResolver = new Definition('Asm\TranslationLoaderBundle\Translation\FileLoaderResolver');
        $this->setDefinition('asm_translation_loader.file_loader_resolver', $fileLoaderResolver);

        $translationManager = new Definition(
            '\Asm\TranslationLoaderBundle\Tests\DependencyInjection\Compiler\DummyTranslationManager'
        );
        $this->setDefinition('asm_translation_loader.translation_manager', $translationManager);
        $loaders = array(
            'csv' => array(
                'class' => 'Symfony\Component\Translation\Loader\CsvFileLoader',
            ),
            'dat' => array(
                'class' => 'Symfony\Component\Translation\Loader\IcuDatFileLoader',
            ),
            'db' => array(
                'class' => 'Asm\TranslationLoaderBundle\Translation\DatabaseLoader',
                'arguments' => array(
                    new Reference('asm_translation_loader.translation_manager')
                )
            ),
            'ini' => array(
                'class' => 'Symfony\Component\Translation\Loader\IniFileLoader',
            ),
            'mo' => array(
                'class' => 'Symfony\Component\Translation\Loader\MoFileLoader',
            ),
            'php' => array(
                'class' => 'Symfony\Component\Translation\Loader\PhpFileLoader',
            ),
            'po' => array(
                'class' => 'Symfony\Component\Translation\Loader\PoFileLoader',
            ),
            'qt' => array(
                'class' => 'Symfony\Component\Translation\Loader\QtFileLoader',
            ),
            'res' => array(
                'class' => 'Symfony\Component\Translation\Loader\IcuResFileLoader',
            ),
            'xliff' => array(
                'class' => 'Symfony\Component\Translation\Loader\XliffFileLoader',
            ),
            'yml' => array(
                'class' => 'Symfony\Component\Translation\Loader\YamlFileLoader',
            ),
        );

        foreach ($loaders as $alias => $attributes) {
            $loader = new Definition($attributes['class']);

            if (isset($attributes['arguments'])) {
                $loader->setArguments($attributes['arguments']);
            }

            $loader->addTag('translation.loader', array('alias' => $alias));
            $this->setDefinition('translation.loader.'.$alias, $loader);
        }
    }

    /**
     * @return \Asm\TranslationLoaderBundle\Translation\FileLoaderResolver
     */
    private function getFileLoaderResolver()
    {
        return $this->container->get('asm_translation_loader.file_loader_resolver');
    }
}

class DummyTranslationManager implements TranslationManagerInterface
{
    public function createTranslation()
    {
    }

    public function findTranslationBy(array $criteria)
    {
    }

    public function findAllTranslations()
    {
    }

    public function findTranslationsBy(array $criteria)
    {
    }

    public function findTranslationsByLocaleAndDomain($locale, $domain = 'messages')
    {
    }

    public function updateTranslation(TranslationInterface $translation)
    {
    }

    public function removeTranslation(TranslationInterface $translation)
    {
    }

    public function findTranslationFreshness($timestamp)
    {
    }
}
