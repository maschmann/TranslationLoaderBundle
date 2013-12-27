<?php
/**
 * @namespace
 */
namespace Asm\TranslationLoaderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Asm\TranslationLoaderBundle\Entity\Translation;

/**
 * @author marc aschmann <maschmann@gmail.com>
 *
 * @uses Doctrine\Common\DataFixtures\FixtureInterface
 * @uses Doctrine\Common\Persistence\ObjectManager
 * @uses Asm\TranslationLoaderBundle\Entity\Translation
 * @package Asm\TranslationLoaderBundle\DataFixtures\ORM
 */
class LoadTranslationData implements FixtureInterface
{

    /**
     * navigation data for pr-populating tables
     *
     * @var array
     */
    private $arrTranslations = array(
        array(
            'key'         => 'test1',
            'locale'      => 'en_US',
            'domain'      => 'messages',
            'translation' => 'This is test1',
        ),
        array(
            'key'         => 'test2',
            'locale'      => 'de',
            'domain'      => 'messages',
            'translation' => 'Das ist test2',
        ),
        array(
            'key'         => 'test3',
            'locale'      => 'de_DE',
            'domain'      => 'messages',
            'translation' => 'Das ist test3',
        ),
        array(
            'key'         => 'test4',
            'locale'      => 'en_US',
            'domain'      => 'test',
            'translation' => 'domain test4',
        ),
    );


    /**
     * default loader method
     *
     * @param ObjectManager $manager
     */
    public function load( ObjectManager $manager )
    {
        foreach ($this->arrTranslations as $val) {
            /** @var \Asm\TranslationLoaderBundle\Entity\Translation $translation */
            $translation = new Translation();
            $translation->setTransKey($val['key']);
            $translation->setTransLocale($val['locale']);
            $translation->setMessageDomain($val['domain']);
            $translation->setTranslation($val['translation']);

            $manager->persist($translation);
            $manager->flush();
        }
    }
}
