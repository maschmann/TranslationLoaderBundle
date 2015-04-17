<?php
/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Translation repository class for the Doctrine ORM storage layer implementation.
 *
 * @package Asm\TranslationLoaderBundle\Entity
 * @author Marc Aschmann <maschmann@gmail.com>
 */
class TranslationRepository extends EntityRepository
{

    /**
     * Check for translations younger than timestamp.
     *
     * @param integer $timestamp
     * @return integer
     */
    public function findTranslationFreshness($timestamp)
    {
        return $this
            ->createQueryBuilder('t')
            ->select('count(t.transKey)')
            ->where('t.dateUpdated > :timestamp')
            ->setParameter('timestamp', $timestamp, \PDO::PARAM_INT)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get a filtered list of translations.
     *
     * @param array $criteria
     * @return array
     */
    public function getTranslationList(array $criteria)
    {
        $queryBuilder = $this
            ->createQueryBuilder('t')
            ->select('t');

        // filter
        if (true === isset($criteria['filter']) && '' != $criteria['filter']) {
            if (false === isset($criteria['value']) || '' != $criteria['value']) {
                $queryBuilder->where(
                    $queryBuilder->expr()->like(
                        't.' . $criteria['filter'],
                        $queryBuilder->expr()->literal($criteria['value'].'%')
                    )
                );
            }
        }

        // order
        if (true === isset($criteria['order']) && '' != $criteria['order']) {
            if (false === isset($criteria['type']) || '' != $criteria['type']) {
                $criteria['type'] = 'ASC';
            }

            $queryBuilder->addOrderBy('t.' . $criteria['order'], $criteria['type']);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
