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
     * @param array $params
     * @return array
     */
    public function getTranslationList(array $params)
    {
        $queryBuilder = $this
            ->createQueryBuilder('t')
            ->select('t');

        // filter
        if (true === isset($params['filter']) && '' != $params['filter']) {
            if (false === isset($params['value']) || '' != $params['value']) {
                $queryBuilder->where(
                    $queryBuilder->expr()->like(
                        't.' . $params['filter'],
                        $queryBuilder->expr()->literal($params['value'].'%')
                    )
                );
            }
        }

        // order
        if (true === isset($params['order']) && '' != $params['order']) {
            if (false === isset($params['type']) || '' != $params['type']) {
                $params['type'] = 'ASC';
            }

            $queryBuilder->addOrderBy('t.' . $params['order'], $params['type']);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
