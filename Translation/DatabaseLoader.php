<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Translation
 */
namespace Asm\TranslationLoaderBundle\Translation;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * Class DatabaseLoader
 *
 * @package Asm\TranslationLoaderBundle\Service
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Doctrine\ORM\EntityManager
 * @uses Symfony\Component\Translation\Loader\LoaderInterface
 * @uses Symfony\Component\Translation\MessageCatalogue
 */
class DatabaseLoader implements LoaderInterface
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    private $doctrine;


    /**
     * manager to use for db loading
     *
     * @var string
     */
    private $manager;


    /**
     * default constructor
     *
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine Registry
     * @param string $manager
     */
    public function __construct(Registry $doctrine, $manager)
    {
        $this->doctrine = $doctrine;
        $this->manager  = $manager;
    }

    /**
     * load messages from db
     *
     * @param string $resource translation key
     * @param string $locale current locale
     * @param string $messageDomain message domain
     * @return \Symfony\Component\Translation\MessageCatalogue
     */
    public function load($resource, $locale, $messageDomain = 'messages')
    {
        $find = array(
            'transLocale'   => $locale,
            'messageDomain' => $messageDomain,
        );

        if (!empty($resource)) {
            $find['translation'] = $resource;
        }

        // get our translations, obviously
        $translations = $this->doctrine
            ->getManager($this->manager)
            ->getRepository('AsmTranslationLoaderBundle:Translation')
            ->findBy($find);

        $catalogue = new MessageCatalogue($locale);

        /** @var \Asm\TranslationLoaderBundle\Entity\Translation $translation */
        foreach ($translations as $translation) {
            $catalogue->set($translation->getTransKey(), $translation->getTranslation(), $messageDomain);
        }

        return $catalogue;
    }
}
