<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Translation
 */
namespace Asm\TranslationLoaderBundle\Translation;

use Asm\TranslationLoaderBundle\Model\TranslationManagerInterface;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Class DatabaseLoader
 *
 * @package Asm\TranslationLoaderBundle\Service
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Symfony\Component\Translation\Loader\LoaderInterface
 * @uses Symfony\Component\Translation\MessageCatalogue
 */
class DatabaseLoader implements LoaderInterface
{
    /**
     * @var TranslationManagerInterface
     */
    private $translationManager;

    /**
     * default constructor
     *
     * @param TranslationManagerInterface $translationManager
     */
    public function __construct(TranslationManagerInterface $translationManager)
    {
        $this->translationManager = $translationManager;
    }

    /**
     * load messages from db
     *
     * @todo check what kind of resource this is and maybe create database resource?
     * @param string $resource translation key
     * @param string $locale current locale
     * @param string $messageDomain message domain
     * @return \Symfony\Component\Translation\MessageCatalogue
     */
    public function load($resource, $locale, $messageDomain = 'messages')
    {
        // get our translations, obviously
        $translations = $this->translationManager
            ->findTranslationsByLocaleAndDomain($locale, $messageDomain);

        $catalogue = new MessageCatalogue($locale);

        foreach ($translations as $translation) {
            $catalogue->set($translation->getTransKey(), $translation->getTranslation(), $messageDomain);
        }

        return $catalogue;
    }
}
