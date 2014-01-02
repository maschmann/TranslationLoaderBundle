<?php
/**
 * @namespace Asm\TranslationLoaderBundle\Repository
 */
namespace Asm\TranslationLoaderBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TranslationRepository
 *
 * @package Asm\TranslationLoaderBundle\Repository
 * @author  marc aschmann <maschman@gmail.com>
 * @uses Doctrine\ORM\EntityRepository
 */
class TranslationRepository extends EntityRepository
{

    /**
     * get all message domain/locale combinations
     *
     * @return array
     */
    public function findByKeys()
    {
        $query = $this->createQueryBuilder('t')
            ->select('DISTINCT CONCAT( t.messageDomain, \'.\', t.transLocale, \'.db\' ) AS filename')
            ->getQuery();

        return $query->getResult();
    }


    /**
     * get all available domain/locale combinations
     *
     * @param string $locale
     * @param string $domain
     * @return array
     */
    public function findByLocaleDomain($locale = null, $domain = null)
    {
        if (!empty($locale) && !empty($domain)) {

            $query = $this->createQueryBuilder('t')
                ->select('DISTINCT t.messageDomain, t.transLocale')
                ->where('t.messageDomain = :domain')
                ->andWhere('t.transLocale = :locale')
                ->setParameter('domain', $domain)
                ->setParameter('locale', $locale)
                ->getQuery();
        } else {
            $query = $this->createQueryBuilder('t')
                ->select('DISTINCT t.messageDomain, t.transLocale')
                ->getQuery();
        }

        return $query->getResult();
    }


    /**
     * find all translations for given locale/domain combination
     *
     * @param string $locale
     * @param string $domain
     * @return array
     */
    public function findByLocaleDomainTranslation($locale, $domain = 'messages')
    {
        $query = $this->createQueryBuilder('t')
            ->select('t.transKey, t.translation')
            ->where('t.transLocale = :locale')
            ->andWhere('t.messageDomain = :domain')
            ->setParameter('locale', $locale)
            ->setParameter('domain', $domain)
            ->getQuery();

        return $query->getResult();
    }
}
