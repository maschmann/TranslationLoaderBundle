<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Command
 */
namespace Asm\TranslationLoaderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DumpTranslationFiles
 *
 * @package Asm\TranslationLoaderBundle\Command
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
 * @uses Symfony\Component\Console\Input\InputArgument
 * @uses Symfony\Component\Console\Input\InputInterface
 * @uses Symfony\Component\Console\Input\InputOption
 * @uses Symfony\Component\Console\Output\OutputInterface
 */
class ImportTranslationFilesCommand extends ContainerAwareCommand
{
    /**
     * command configuration
     */
    protected function configure()
    {
        $this
            ->setName('asm:translations:import')
            ->setDescription('dump translations from database to files')
            ->addArgument(
                'domain',
                InputArgument::OPTIONAL,
                'specific message domain to dump'
            )
            ->addArgument(
                'loacle',
                InputArgument::OPTIONAL,
                'specific locale to dump'
            )
            ->addOption(
                'clear',
                'c',
                null,
                'clear database before import'
            );
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
