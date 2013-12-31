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
use Asm\TranslationLoaderBundle\Entity\Translation;

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
class CreateTranslationCommand extends ContainerAwareCommand
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
        $domain     = $input->getArgument('domain');
        $em         = $this
            ->getContainer()->get('doctrine')
            ->getManager($this->getContainer()->getParameter('asm_translation_loader.database.entity_manager'));
        $repository = $em->getRepository('AsmTranslationLoaderBundle:Translation');
        $action     = 'updated';

        /** @var \Asm\TranslationLoaderBundle\Entity\Translation $translation */
        $translation = $repository->findOneBy(
            array(
                'transKey'      => $input->getArgument('key'),
                'transLocale'   => $input->getArgument('locale'),
                'messageDomain' => $domain,
            )
        );

        // insert if no entry exists
        if (!$translation) {
            /** @var \Asm\TranslationLoaderBundle\Entity\Translation $translation */
            $translation = new Translation();
            $translation->setTransKey($input->getArgument('key'));
            $translation->setTransLocale($input->getArgument('locale'));
            $translation->setMessageDomain($domain);
            $translation->setDateCreated();

            $action = 'created';
        }

        // and in either case we want to add a message :-)
        $translation->setTranslation($input->getArgument('translation'));
        $translation->setDateUpdated();

        $output->writeln('<info>' . $action . ' translation</info>');
        $em->persist($translation);
        $em->flush();
    }
}
