<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Command
 */
namespace Asm\TranslationLoaderBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DumpTranslationFiles
 *
 * @package Asm\TranslationLoaderBundle\Command
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Component\Console\Input\InputArgument
 * @uses Symfony\Component\Console\Input\InputInterface
 * @uses Symfony\Component\Console\Input\InputOption
 * @uses Symfony\Component\Console\Output\OutputInterface
 */
class GenerateDummyFilesCommand extends BaseTranslationCommand
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

        $translationPath = $this->getKernel()->getRootDir().'/Resources/translations/';
        $fs = $this->getFilesystem();

        // create directory for translations if not exists
        if (!$fs->exists($translationPath)) {
            $fs->mkdir($translationPath);
        }

        $translationManager = $this->getTranslationManager();
        $translations = $translationManager->findAllTranslations();

        foreach ($translations as $translation) {
            $filename = $translation->getMessageDomain().'.'.$translation->getTransLocale().'.db';

            if (!$fs->exists($translationPath.$filename)) {
                $fs->touch($translationPath.$filename);
            }
        }

        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>finished!</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
    }
}
