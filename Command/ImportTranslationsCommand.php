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
use Symfony\Component\Translation\Catalogue\DiffOperation;
use Symfony\Component\Translation\Catalogue\MergeOperation;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Finder\Finder;

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
 * @uses Symfony\Component\Translation\Catalogue\DiffOperation
 * @uses Symfony\Component\Translation\Catalogue\MergeOperation
 * @uses Symfony\Component\Translation\MessageCatalogue
 */
class ImportTranslationsCommand extends ContainerAwareCommand
{

    /**
     * message catalogue container
     *
     * @var array
     */
    private $catalogues = array();


    /**
     * translation loader container
     *
     * @var array
     */
    private $loaders = array();

    /**
     * command configuration
     */
    protected function configure()
    {
        $this
            ->setName('asm:translations:import')
            ->setDescription('Import translations from all bundles.')
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
     * @throws \ErrorException
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>Translation file importer</info>');
        $output->writeln('<info>--------------------------------------------------------------------------------</info>');
        $output->writeln('<info>importing all available translation files ...</info>');

        $translationWriter = $container->get('translation.writer');
        $supportedFormats = $translationWriter->getFormats();

        // iterate all bundles and get their translations
        foreach (array_keys($container->getParameter('kernel.bundles')) as $bundle) {
            $currentBundle = $this->getApplication()->getKernel()->getBundle($bundle);
            $translationPath = $currentBundle->getPath().'/Resources/translations';

            // load any existing translation files
            if (is_dir($translationPath)) {
                $output->writeln('<info>searching ' . $bundle . ' translations</info>');

                $finder = new Finder();
                $files = $finder
                    ->files()
                    ->in($translationPath);

                foreach ($files as $file) {
                    $extension = explode('.', $file->getFilename());
                    // domain.locale.extension
                    if (3 == count($extension)) {
                        $fileExtension = array_pop($extension);
                        if (in_array($fileExtension, $supportedFormats)) {
                            $locale = array_pop($extension);
                            $domain = array_pop($extension);

                            if (empty($this->catalogues[$locale])) {
                                $this->catalogues[$locale] = new MessageCatalogue($locale);
                            }

                            if (empty($this->loaders[$fileExtension])) {
                                try {
                                    $this->loaders[$fileExtension] = $container->get('translation.loader.' . $fileExtension);
                                } catch (\Exception $e) {
                                    throw new \ErrorException('could not find loader for ' . $fileExtension . ' files!');
                                }
                            }

                            $output->writeln('<comment>loading ' . $file->getFilename() . ' with locale ' . $locale . ' and domain ' . $domain . '</comment>');
                            $currentCatalogue = $this->loaders[$fileExtension]->load($file->getPathname(), $locale, $domain);
                            $this->catalogues[$locale]->addCatalogue($currentCatalogue);
                        }
                    }
                }
                $output->writeln('');
            }
        }

        print_r($this->catalogues);
    }
}
