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
     * @var string $transKey
     * @ORM\Id()
     * @ORM\Column(name="trans_key", type="string", length=255)
     */
    private $transKey;

    /**
     * @var string $transLocale
´    * @ORM\Id()
     * @ORM\Column(name="trans_locale", type="string", length=5)
     */
    private $transLocale;

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
     * @param string $transKey
     */
    public function setTransKey($transKey)
    {
        $this->transKey = $transKey;
    }

    /**
     * @return string
     */
    public function getTransKey()
    {
        return $this->transKey;
    }

    /**
     * @param string $transLocale
     */
    public function setTransLocale($transLocale)
    {
        $this->transLocale = $transLocale;
    }

    /**
     * @return string
     */
    public function getTransLocale()
    {
        return $this->transLocale;
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
