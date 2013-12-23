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
}
