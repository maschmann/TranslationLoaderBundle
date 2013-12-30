<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Entity
 */
namespace Asm\TranslationLoaderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime as DateTime;

/**
 * Class TranslationHistory
 *
 * @package Asm\TranslationLoaderBundle\Entity
 * @author marc aschmann <maschmann@gmail.com>
 * @uses Doctrine\ORM\Mapping ORM
 * @uses \DateTime
 * @ORM\Table(name="translation_history",indexes={@index(name="search_idx", columns={"trans_key", "trans_locale", "message_domain"})})
 * @ORM\Entity(repositoryClass="Asm\TranslationLoaderBundle\Repository\TranslationHistoryRepository")
 */
class TranslationHistory {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $transKey
     * @ORM\Column(name="trans_key", type="string", length=255, nullable=false)
     */
    private $transKey;

    /**
     * @var string $transLocale
     * @ORM\Column(name="trans_locale", type="string", length=5, nullable=false)
     */
    private $transLocale;

    /**
     * @var string $messageDomain
     * @ORM\Column(name="message_domain", type="string", length=255, nullable=false)
     */
    private $messageDomain;

    /**
     * @var integer $userId
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string $userAction
     * @ORM\Column(name="user_action", type="string", length=10, nullable=false)
     */
    private $userAction;

    /**
     * @var string $transValueBefore
     * @ORM\Column(name="trans_value_before", type="text")
     */
    private $transValueBefore;

    /**
     * @var string $transValueAfter
     * @ORM\Column(name="trans_value_after", type="text")
     */
    private $transValueAfter;

    /**
     * @var string $dateOfChange
     * @ORM\Column(name="date_of_change", type="datetime", nullable=false)
     */
    private $dateOfChange;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $dateOfChange
     */
    public function setDateOfChange($dateOfChange=null)
    {
        if (empty($dateOfChange)) {
            $dateOfChange = new DateTime();
        }
        $this->dateOfChange = $dateOfChange;
    }

    /**
     * @return string
     */
    public function getDateOfChange()
    {
        return $this->dateOfChange;
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
     * @param string $transValueAfter
     */
    public function setTransValueAfter($transValueAfter)
    {
        $this->transValueAfter = $transValueAfter;
    }

    /**
     * @return string
     */
    public function getTransValueAfter()
    {
        return $this->transValueAfter;
    }

    /**
     * @param string $transValueBefore
     */
    public function setTransValueBefore($transValueBefore)
    {
        $this->transValueBefore = $transValueBefore;
    }

    /**
     * @return string
     */
    public function getTransValueBefore()
    {
        return $this->transValueBefore;
    }

    /**
     * @param mixed $userAction
     */
    public function setUserAction($userAction)
    {
        $this->userAction = $userAction;
    }

    /**
     * @return mixed
     */
    public function getUserAction()
    {
        return $this->userAction;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId=null)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
