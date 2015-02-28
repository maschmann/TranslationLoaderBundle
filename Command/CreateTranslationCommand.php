<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Command
 */
namespace Asm\TranslationLoaderBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
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
class CreateTranslationCommand extends BaseTranslationCommand
{
    /**
     * command configuration
     */
    protected function configure()
    {
        $this
            ->setName('asm:translations:create')
            ->setDescription('add new translation to db')
            ->addArgument(
                'key',
                InputArgument::REQUIRED,
                'translation key'
            )
            ->addArgument(
                'locale',
                InputArgument::REQUIRED,
                'locale (e.g. en, en_US, ...)'
            )
            ->addArgument(
                'translation',
                InputArgument::REQUIRED,
                'translation text'
            )
            ->addArgument(
                'domain',
                InputArgument::OPTIONAL,
                'specific message domain, default "messages"',
                'messages'
            );
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getArgument('domain');
        $action = 'updated';

        $translationManager = $this->getTranslationManager();

        $translation = $translationManager->findTranslationBy(
            array(
                'transKey'      => $input->getArgument('key'),
                'transLocale'   => $input->getArgument('locale'),
                'messageDomain' => $domain,
            )
        );

        // insert if no entry exists
        if (!$translation) {
            $translation = $translationManager->createTranslation();
            $translation->setTransKey($input->getArgument('key'));
            $translation->setTransLocale($input->getArgument('locale'));
            $translation->setMessageDomain($domain);

            $action = 'created';
        }

        // and in either case we want to add a message :-)
        $translation->setTranslation($input->getArgument('translation'));

        $output->writeln('<info>' . $action . ' translation</info>');
        $translationManager->updateTranslation($translation);
    }
}
