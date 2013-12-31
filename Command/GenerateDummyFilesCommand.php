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
class GenerateDummyFilesCommand extends ContainerAwareCommand
{
    /**
     * command configuration
     */
    protected function configure()
    {
        $this
            ->setName('asm:translations:dummy')
            ->setDescription('generate dummy files for translation loader');
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>generating dummy files</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');

        $container = $this->getContainer();
        $translationPath = $container->get('kernel')->getRootDir().'/Resources/translations/';

        // create directory for translations if not exists
        if (!is_dir($translationPath)) {
            mkdir($translationPath);
        }

        $fileList = $container->get('doctrine')
            ->getManager($container->getParameter('asm_translation_loader.database.entity_manager'))
            ->getRepository('AsmTranslationLoaderBundle:Translation')
            ->findByKeys();

        foreach ($fileList as $file) {
            $output->writeln('<comment>generating ' . $translationPath . $file['filename'] . '</comment>');
            file_put_contents($translationPath . $file['filename'], '');
        }

        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>finished!</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
    }
}
