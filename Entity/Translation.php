<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Entity
 */
namespace Asm\TranslationLoaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime as DateTime;

/**
 * translation entity
 *
 * @package Asm\TranslationLoaderBundle\Entity
 * @author  marc aschmann <maschmann@gmail.com>
 * @uses Doctrine\ORM\Mapping ORM
 * @uses \DateTime
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
     * @var string $dateCreated
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;


    /**
     * @var string $dateUpdated
     * @ORM\Column(name="date_updated", type="datetime")
     */
    private $dateUpdated;


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

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated = null)
    {
        if (empty($dateCreated)) {
            $dateCreated = new \DateTime();
        }
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated = null)
    {
        if (empty($dateUpdated)) {
            $dateUpdated = new \DateTime();
        }
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }
}
