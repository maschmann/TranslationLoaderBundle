<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Tests\Command;

use Asm\TranslationLoaderBundle\Test\TranslationTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Base class for command tests.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
abstract class CommandTest extends TranslationTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        foreach (static::$kernel->getBundles() as $bundle) {
            if ($bundle instanceof Bundle) {
                $bundle->registerCommands($this->application);
            }
        }
    }

    /**
     * Executes the command with the given name.
     *
     * @param string $name      The command name
     * @param array  $arguments Optional command arguments
     */
    protected function execute($name, array $arguments = array())
    {
        $command = $this->application->find($name);

        if ($command instanceof ContainerAwareInterface) {
            $command->setContainer(self::$kernel->getContainer());
        }

        $tester = new CommandTester($command);
        $tester->execute(array_merge($arguments, array('command' => $command->getName())));
    }
}
