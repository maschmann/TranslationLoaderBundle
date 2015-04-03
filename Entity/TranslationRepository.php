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
     * @param string $order
     * @param string $type
     * @param array $filter
     * @return array
     */
    public function getTranslationList($order = '', $type = 'ASC', $filter = array())
    {
        $queryBuilder = $this
            ->createQueryBuilder('t')
            ->select('t');

        if ('' !== $order) {
            if (empty($type)) {
                $type = 'ASC';
            }

            $queryBuilder->orderBy('t.' . $order, $type);
        }

        if (!empty($filter)) {
            // do something
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
