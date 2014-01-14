<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Class DumpTranslationFiles
 *
 * @package Asm\TranslationLoaderBundle\Command
 * @author marc aschmann <maschmann@gmail.com>
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 * @uses Symfony\Component\Console\Input\InputArgument
 * @uses Symfony\Component\Console\Input\InputInterface
 * @uses Symfony\Component\Console\Input\InputOption
 * @uses Symfony\Component\Console\Output\OutputInterface
 * @uses Symfony\Component\Translation\MessageCatalogue
 */
class DumpTranslationFilesCommand extends BaseTranslationCommand
{

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('asm:translations:dump')
            ->setDescription('dump translations from database to files')
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_OPTIONAL,
                'File format, default yml ',
                'yml'
            )
            ->addOption(
                'locale',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Specific locale to dump'
            )
            ->addOption(
                'domain',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Specific message domain to dump, requires locale!',
                'messages'
            );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>generating translation files</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');

        $translationPath = $this->getKernel()->getRootDir().'/Resources/translations/';

        // create directory for translations if it does not exist
        if (!$this->getFilesystem()->exists($translationPath)) {
            $this->getFilesystem()->mkdir($translationPath);
        }

        $translationManager = $this->getTranslationManager();
        $locale = $input->getOption('locale');
        $domain = $input->getOption('domain');
        $criteria = array(
            'messageDomain' => $domain,
        );

        if (null !== $locale) {
            $criteria['transLocale'] = $locale;
        }

        $translations = $translationManager->findTranslationsBy($criteria);

        // create message catalogues first
        $catalogues = array();
        foreach ($translations as $translation) {
            $locale = $translation->getTransLocale();

            if (!isset($catalogues[$locale])) {
                $catalogues[$locale] = new MessageCatalogue($locale);
            }

            /** @var MessageCatalogue $catalogue */
            $catalogue = $catalogues[$locale];

            $catalogue->set(
                $translation->getTransKey(),
                $translation->getTranslation(),
                $translation->getMessageDomain()
            );
        }

        // dump the generated catalogues
        foreach ($catalogues as $locale => $catalogue) {
            $output->writeln(sprintf(
                '<comment>generating catalogue for locale %s</comment>',
                $locale
            ));
            $this->getTranslationWriter()->writeTranslations(
                $catalogue,
                $input->getOption('format'),
                array('path' => $translationPath)
            );
        }

        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>finished!</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
    }
}
