<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Entity
 */
namespace Asm\TranslationLoaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * translation entity
 *
 * @package Asm\TranslationLoaderBundle\Entity
 * @author  marc aschmann <maschmann@gmail.com>
 * @ORM\Table(name="translation")
 * @ORM\Entity(repositoryClass="Asm\TranslationLoaderBundle\Repository\TranslationRepository")
 */
class Translation
{

    /**
     * @var integer $id
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $key
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     */
    private $key;

    /**
     * @var string $locale
´    * @ORM\Id()
     * @ORM\Column(type="string", length=5)
     */
    private $locale;

    /**
     * @var string $messageDomain
     * @ORM\Id()
     * @ORM\Column(name="message_domain", type="string", length=255)
     */
    private $messageDomain;

    /**
     * @var string $translation
     * @ORM\Column(type="text")
     */
    private $translation;

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $messageDomain
     */
    public function setMessageDomain($messageDomain)
    {
        $this->messageDomain = $messageDomain;
    }

    /**
     * @return string
     */
    public function getMessageDomain()
    {
        return $this->messageDomain;
    }

    /**
     * @param string $translation
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;
    }

    /**
     * @return string
     */
    public function getTranslation()
    {
        return $this->translation;
    }

} 