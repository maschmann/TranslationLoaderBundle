<?php

/*
 * This file is part of the AsmTranslationLoaderBundle package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\TranslationLoaderBundle\Doctrine;

use Asm\TranslationLoaderBundle\Model\TranslationInterface;
use Asm\TranslationLoaderBundle\Model\TranslationManager as BaseTranslationManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * TranslationManager implementation supporting Doctrine.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationManager extends BaseTranslationManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @param ObjectManager $objectManager Object manager for translation entities
     * @param string        $class         Translation model class name
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        parent::__construct($class);

        $this->objectManager = $objectManager;
        $this->repository = $objectManager->getRepository($class);
    }

    /**
     * {@inheritDoc}
     */
    public function findTranslationBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findTranslationsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function updateTranslation(TranslationInterface $translation)
    {
        $translation->setDateUpdated(new \DateTime());

        $this->objectManager->persist($translation);
        $this->objectManager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function removeTranslation(TranslationInterface $translation)
    {
        $this->objectManager->remove($translation);
        $this->objectManager->flush();
    }
}
