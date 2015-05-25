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

use Asm\TranslationLoaderBundle\Model\TranslationHistoryInterface;
use Asm\TranslationLoaderBundle\Model\TranslationHistoryManager as BaseTranslationHistoryManager;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * TranslationHistoryManager implementation supporting Doctrine.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class TranslationHistoryManager extends BaseTranslationHistoryManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager Object manager for translation history entities
     * @param string        $class         Translation history model class name
     */
    public function __construct(ObjectManager $objectManager, $class)
    {
        parent::__construct($class);

        $this->objectManager = $objectManager;
    }

    /**
     * {@inheritDoc}
     */
    public function updateTranslationHistory(TranslationHistoryInterface $translationHistory, $clear = false)
    {
        $this->objectManager->persist($translationHistory);
        $this->objectManager->flush();

        if ($clear) {
            $this->objectManager->clear();
        }
    }
}
