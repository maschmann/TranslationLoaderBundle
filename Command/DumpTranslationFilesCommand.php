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
use Symfony\Component\Translation\MessageCatalogue;

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
 * @uses Symfony\Component\Translation\MessageCatalogue
 */
class DumpTranslationFilesCommand extends ContainerAwareCommand
{

    /**
     * command configuration
     */
    protected function configure()
    {
        $this
            ->setName('asm:translations:dump')
            ->setDescription('dump translations from database to files')
            ->addArgument(
                'domain',
                InputArgument::OPTIONAL,
                'specific message domain to dump, requires locale!',
                'messages'
            )
            ->addArgument(
                'locale',
                InputArgument::OPTIONAL,
                'specific locale to dump'
            )
            ->addArgument(
                'format',
                InputArgument::OPTIONAL,
                'file format, default yml',
                'yml'
            );
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>generating translation files</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');

        $container       = $this->getContainer();
        $translationPath = $container->get('kernel')->getRootDir().'/Resources/translations/';

        // create directory for translations if not exists
        if (!is_dir($translationPath)) {
            mkdir($translationPath);
        }

        $repository = $container->get('doctrine')
            ->getManager($container->getParameter('asm_translation_loader.database.entity_manager'))
            ->getRepository('AsmTranslationLoaderBundle:Translation');
        $transList = $repository->findByLocaleDomain(
            $input->getArgument('locale'),
            $input->getArgument('domain')
        );

        /** @var \Symfony\Component\Translation\Writer\TranslationWriter $writer */
        $writer    = $container->get('translation.writer');
        $options   = array('path' => $translationPath);

        foreach ($transList as $trans) {
            $locale = $trans['transLocale'];
            $domain = $trans['messageDomain'];

            $output->writeln(
                '<comment>generating catalogue for ' .
                $domain. ' with locale ' .
                $locale . '</comment>'
            );

            $writer->writeTranslations(
                $this->getMessages($locale, $domain, $repository),
                $input->getArgument('format'),
                $options
            );
        }

        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>finished!</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
    }


    /**
     * @param string $locale
     * @param string $domain
     * @param TranslationRepostitory $repository
     * @return MessageCatalogue
     */
    private function getMessages($locale, $domain, $repository)
    {
        $catalogue = new MessageCatalogue($locale);
        $messages  = array();

        foreach($repository->findByLocaleDomainTranslation($locale, $domain) as $translation) {
            $messages[ $translation['transKey'] ] = $translation['translation'];
        }
        $catalogue->add($messages, $domain);

        return $catalogue;
    }
}
