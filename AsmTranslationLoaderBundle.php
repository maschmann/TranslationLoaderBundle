<?php

namespace Asm\TranslationLoaderBundle;

use Asm\TranslationLoaderBundle\DependencyInjection\Compiler\AddResourcePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AsmTranslationLoaderBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AddResourcePass());
    }
}
