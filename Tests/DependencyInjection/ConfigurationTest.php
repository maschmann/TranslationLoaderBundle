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
use Asm\TranslationLoaderBundle\DependencyInjection\Configuration;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionConfigurationTestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConfigurationTest extends AbstractExtensionConfigurationTestCase
{
    public function testLocaleWithoutDomain()
    {
        $configs = $this->buildResourcesConfig(array('en' => null));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array(null)), $config['resources']);
    }

    public function testLocaleWithOneDomain()
    {
        $configs = $this->buildResourcesConfig(array('en' => 'foo'));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo')), $config['resources']);
    }

    public function testLocaleWithMultipleDomains()
    {
        $configs = $this->buildResourcesConfig(array('en' => array('foo', 'bar')));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo', 'bar')), $config['resources']);
    }

    public function testLocaleWithoutDomainFromXml()
    {
        $configs = $this->buildResourcesConfig(array(array('locale' => 'en')));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array(null)), $config['resources']);
    }

    public function testLocaleWithOneDomainFromXml()
    {
        $configs = $this->buildResourcesConfig(array(array('locale' => 'en', 'domain' => 'foo')));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo')), $config['resources']);
    }

    public function testLocaleWithMultipleDomainsFromXml()
    {
        $configs = $this->buildResourcesConfig(array(
            array(
                'locale' => 'en',
                'domain' => array('foo', 'bar'),
            )
        ));
        $config = $this->process($configs);

        $this->assertEquals(array('en' => array('foo', 'bar')), $config['resources']);
    }

    public function testAdditionalLoaderFromXml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(
                    'foo' => 'translation.loader.bar',
                    'xlf' => 'translation.loader.xliff',
                    'yaml' => 'translation.loader.yml',
                ),
                'history' => array('enabled' => false),
                'translation_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationManager',
                'translation_history_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager',
                'translation_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
                'translation_history_class' => 'Asm\TranslationLoaderBundle\Entity\TranslationHistory',
            ),
            array(__DIR__.'/Fixtures/additional_loader.xml')
        );
    }

    public function testAdditionalLoaderFromYaml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(
                    'foo' => 'translation.loader.bar',
                    'xlf' => 'translation.loader.xliff',
                    'yaml' => 'translation.loader.yml',
                ),
                'history' => array('enabled' => false),
                'translation_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationManager',
                'translation_history_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager',
                'translation_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
                'translation_history_class' => 'Asm\TranslationLoaderBundle\Entity\TranslationHistory',
            ),
            array(__DIR__.'/Fixtures/additional_loader.yml')
        );
    }

    public function testReplacedLoaderFromXml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(
                    'xlf' => 'translation.loader.xliff',
                    'yaml' => 'translation.loader.foo',
                ),
                'history' => array('enabled' => false),
                'translation_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationManager',
                'translation_history_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager',
                'translation_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
                'translation_history_class' => 'Asm\TranslationLoaderBundle\Entity\TranslationHistory',
            ),
            array(__DIR__.'/Fixtures/replaced_loader.xml')
        );
    }

    public function testReplacedLoaderFromYml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(
                    'xlf' => 'translation.loader.xliff',
                    'yaml' => 'translation.loader.foo',
                ),
                'history' => array('enabled' => false),
                'translation_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationManager',
                'translation_history_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager',
                'translation_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
                'translation_history_class' => 'Asm\TranslationLoaderBundle\Entity\TranslationHistory',
            ),
            array(__DIR__.'/Fixtures/replaced_loader.yml')
        );
    }

    public function testReplacedTranslationManagerFromYml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(),
                'history' => array('enabled' => false),
                'translation_manager' => 'translation_manager.foo',
                'translation_history_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager',
                'translation_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
                'translation_history_class' => 'Asm\TranslationLoaderBundle\Entity\TranslationHistory',
            ),
            array(__DIR__.'/Fixtures/replaced_translation_manager.yml')
        );
    }

    public function testReplacedTranslationHistoryManagerFromYml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(),
                'history' => array('enabled' => false),
                'translation_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationManager',
                'translation_history_manager' => 'translation_history_manager.foo',
                'translation_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
                'translation_history_class' => 'Asm\TranslationLoaderBundle\Entity\TranslationHistory',
            ),
            array(__DIR__.'/Fixtures/replaced_translation_history_manager.yml')
        );
    }

    public function testReplacedTranslationClassFromYml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(),
                'history' => array('enabled' => false),
                'translation_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationManager',
                'translation_history_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager',
                'translation_class' => 'translation_class.foo',
                'translation_history_class' => 'Asm\TranslationLoaderBundle\Entity\TranslationHistory',
            ),
            array(__DIR__.'/Fixtures/replaced_translation_class.yml')
        );
    }

    public function testReplacedTranslationHistoryClassFromYml()
    {
        $this->assertProcessedConfigurationEquals(
            array(
                'resources' => array(),
                'driver' => 'orm',
                'loaders' => array(),
                'history' => array('enabled' => false),
                'translation_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationManager',
                'translation_history_manager' => 'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager',
                'translation_class' => 'Asm\TranslationLoaderBundle\Entity\Translation',
                'translation_history_class' => 'translation_history_class.foo',
            ),
            array(__DIR__.'/Fixtures/replaced_translation_history_class.yml')
        );
    }

    protected function getContainerExtension()
    {
        return new AsmTranslationLoaderExtension();
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }

    private function process(array $configs)
    {
        $processor = new Processor();

        return $processor->processConfiguration($this->getConfiguration(), $configs);
    }

    private function buildResourcesConfig(array $resources)
    {
        return array(
            array(
                'resources' => $resources,
            )
        );
    }
}
