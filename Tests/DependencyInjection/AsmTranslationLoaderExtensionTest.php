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
use Symfony\Component\DependencyInjection\Reference;

class AsmTranslationLoaderExtensionTest extends AbstractExtensionTestCase
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

        // ensure that the event dispatcher is initialized
        $this->assertContainerBuilderHasService(
            'asm_translation_loader.event_dispatcher',
            'Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.event_dispatcher',
            0,
            new Reference('service_container')
        );

        $this->assertContainerBuilderNotHasService('asm_translation_loader.history.subscriber');
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

    public function testWithEnabledHistoryListener()
    {
        $this->load(array('history' => array('enabled' => true)));

        $this->assertContainerBuilderHasService(
            'asm_translation_loader.history.subscriber',
            'Asm\TranslationLoaderBundle\EventListener\TranslationHistorySubscriber'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.history.subscriber',
            0,
            new Reference('asm_translation_loader.translation_history_manager')
        );

        if (interface_exists('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')) {
            $this->assertContainerBuilderHasServiceDefinitionWithArgument(
                'asm_translation_loader.history.subscriber',
                1,
                new Reference('security.token_storage')
            );
        } else {
            $this->assertContainerBuilderHasServiceDefinitionWithArgument(
                'asm_translation_loader.history.subscriber',
                1,
                new Reference('security.context')
            );
        }

        foreach (array('postPersist', 'postUpdate', 'postRemove') as $event) {
            $this->assertContainerBuilderHasServiceDefinitionWithTag(
                'asm_translation_loader.history.subscriber',
                'asm_translation_loader.event_listener',
                array('event' => $event, 'method' => 'updateHistory')
            );
        }
    }

    public function testOrmDriver()
    {
        $this->load(array('driver' => 'orm'));

        // entity class names
        $this->assertContainerBuilderHasParameter(
            'asm_translation_loader.model.translation.class',
            'Asm\TranslationLoaderBundle\Entity\Translation'
        );
        $this->assertContainerBuilderHasParameter(
            'asm_translation_loader.model.translation_history.class',
            'Asm\TranslationLoaderBundle\Entity\TranslationHistory'
        );

        // the entity manager
        $this->assertContainerBuilderHasService(
            'asm_translation_loader.translation.entity_manager',
            'Doctrine\Common\Persistence\ObjectManager'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.translation.entity_manager',
            0,
            '%asm_translation_loader.database.entity_manager%'
        );

        // the translation manager
        $this->assertContainerBuilderHasService(
            'asm_translation_loader.translation_manager',
            'Asm\TranslationLoaderBundle\Doctrine\TranslationManager'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.translation_manager',
            0,
            new Reference('asm_translation_loader.translation.entity_manager')
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.translation_manager',
            1,
            '%asm_translation_loader.model.translation.class%'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.translation_manager',
            2,
            new Reference('asm_translation_loader.event_dispatcher')
        );

        // the translation history manager
        $this->assertContainerBuilderHasService(
            'asm_translation_loader.translation_history_manager',
            'Asm\TranslationLoaderBundle\Doctrine\TranslationHistoryManager'
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.translation_history_manager',
            0,
            new Reference('asm_translation_loader.translation.entity_manager')
        );
        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'asm_translation_loader.translation_history_manager',
            1,
            '%asm_translation_loader.model.translation_history.class%'
        );
    }

    protected function getContainerExtensions()
    {
        return array(new AsmTranslationLoaderExtension());
    }
}
